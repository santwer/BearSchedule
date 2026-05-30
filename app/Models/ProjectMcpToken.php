<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class ProjectMcpToken extends Model
{
    protected $fillable = [
        'project_id',
        'user_id',
        'token_hash',
        'token_prefix',
        'name',
        'last_used_at',
        'expires_at',
        'revoked_at',
    ];

    protected function casts(): array
    {
        return [
            'last_used_at' => 'datetime',
            'expires_at' => 'datetime',
            'revoked_at' => 'datetime',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param  Builder<ProjectMcpToken>  $query
     * @return Builder<ProjectMcpToken>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->whereNull('revoked_at')
            ->where(function (Builder $query): void {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * @return array{plain: string, prefix: string, hash: string}
     */
    public static function generatePlainToken(): array
    {
        $prefix = bin2hex(random_bytes(4));
        $secret = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
        $plain = $prefix.'_'.$secret;

        return [
            'plain' => $plain,
            'prefix' => $prefix,
            'hash' => hash('sha256', $plain),
        ];
    }

    /**
     * @return array{token: ProjectMcpToken, plain: string}
     */
    public static function createFor(User $user, Project $project, ?string $name = null, ?Carbon $expiresAt = null): array
    {
        $generated = self::generatePlainToken();

        $token = self::query()->create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'token_hash' => $generated['hash'],
            'token_prefix' => $generated['prefix'],
            'name' => $name,
            'expires_at' => $expiresAt,
        ]);

        return [
            'token' => $token,
            'plain' => $generated['plain'],
        ];
    }

    public static function findByPlainToken(string $plainToken): ?self
    {
        $separatorPosition = strpos($plainToken, '_');

        if ($separatorPosition === false) {
            return null;
        }

        $prefix = substr($plainToken, 0, $separatorPosition);
        $hash = hash('sha256', $plainToken);

        $candidates = self::query()
            ->active()
            ->where('token_prefix', $prefix)
            ->get();

        foreach ($candidates as $candidate) {
            if (hash_equals($candidate->token_hash, $hash)) {
                return $candidate;
            }
        }

        return null;
    }

    public function markUsed(): void
    {
        $this->forceFill(['last_used_at' => now()])->save();
    }

    public function revoke(): void
    {
        $this->forceFill(['revoked_at' => now()])->save();
    }
}
