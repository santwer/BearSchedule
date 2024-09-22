<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ShareCollection extends ResourceCollection
{
    protected ?string $shareId = null;
    public function __construct($resource, ?string $shareId = null)
    {
        parent::__construct($resource);
        $this->shareId = $shareId;
    }

    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            'share_url' => route('share.index', $this->shareId),
        ];
    }
}
