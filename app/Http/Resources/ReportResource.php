<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => new ReportTypeResource($this->type),
            'status' => new ReportStatusResource($this->status),
            'filename' => $this->filename,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'generated_at' => $this->generated_at?->format('Y-m-d H:i:s')
        ];
    }
}
