<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('jobs', function (Blueprint $table) {
            $table->string('job_shift', 255)->nullable()->after('job_shift_id');
        });
    }

    public function down(): void {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn('job_shift');
        });
    }
};
