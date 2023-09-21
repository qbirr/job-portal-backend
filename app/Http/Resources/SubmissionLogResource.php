<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\SubmissionLog */
class SubmissionLogResource extends JsonResource {
    public function toArray($request) {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'submission_status_id' => $this->submission_status_id,
            'notes' => $this->notes,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
