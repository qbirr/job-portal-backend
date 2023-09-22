<?php

namespace App\Http\Resources;

use App\Models\JobSubmissionLog;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin JobSubmissionLog */
class JobSubmissionLogResource extends JsonResource {
    public function toArray($request): array {
        return [
            'id' => $this->id,
            'job_id' => $this->job_id,
            'submission_status_id' => $this->submission_status_id,
            'notes' => $this->notes,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
