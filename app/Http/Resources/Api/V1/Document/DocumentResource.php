<?php

namespace App\Http\Resources\Api\V1\Document;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $extension = explode('.', $this->path);
        $extension = end($extension);


        return [
            'id' => $this->ATT_document_id,
            'name' => $this->name,
            'mime_type' => $this->mime_type,
            'has_ocr' => $this->has_ocr,
            'ocr_is_active' => $this->ocr_is_active,
            'ocr_strategy' => $this->ocr_strategy,
            'extension' => $extension,
        ];
    }
}
