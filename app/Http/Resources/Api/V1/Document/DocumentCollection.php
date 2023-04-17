<?php

namespace App\Http\Resources\Api\V1\Document;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DocumentCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            'api_version' => '1.0',
            'api_status' => 'success',
            'api_message' => 'OK',
        ];
    }
}
