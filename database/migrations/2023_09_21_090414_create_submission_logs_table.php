<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('submission_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('company_id');
            $table->bigInteger('submission_status_id');
            $table->text('notes')->nullable();
            $table->bigInteger('user_id');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('submission_logs');
    }
};
