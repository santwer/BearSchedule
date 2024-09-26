<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use RyanChandler\LaravelCloudflareTurnstile\Rules\Turnstile;

class TurnstileRule extends Turnstile
{
    protected function mapErrorCodeToMessage(string $code): string
    {
        return match ($code) {
            'missing-input-secret' => __('The secret parameter was not passed.'),
            'invalid-input-secret' => __('The secret parameter was invalid or did not exist.'),
            'missing-input-response' => __('The response parameter was not passed.'),
            'invalid-input-response' => __('The response parameter is invalid or has expired.'),
            'bad-request' => __('The request was rejected because it was malformed.'),
            'timeout-or-duplicate' => __('The response parameter has already been validated before.'),
            'internal-error' => __('An internal error happened while validating the response.'),
            default => __('An unexpected error occurred.'),
        };
    }
}
