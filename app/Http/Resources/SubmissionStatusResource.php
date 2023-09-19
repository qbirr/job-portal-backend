<?php

namespace App\Http\Resources;

use App\Models\SubmissionStatus;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;

/** @mixin SubmissionStatus */
class SubmissionStatusResource extends JsonResource {
    #[ArrayShape(['id' => "int", 'status_name' => "string"])]
    public function toArray($request): array {
        return [
            'id' => $this->id,
            'status_name' => $this->status_name,
        ];
    }
}
