<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('job_submission_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id');
            $table->foreignId('submission_status_id');
            $table->text('notes')->nullable();
            $table->foreignId('user_id');
            $table->timestamps();
        });

        Artisan::call('db:seed',
            ['--class' => 'SubmissionStatusSeeder', '--force' => true]);
    }

    public function down(): void {
        Schema::dropIfExists('job_submission_logs');
    }
};
