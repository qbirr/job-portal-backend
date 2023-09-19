<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('companies', function (Blueprint $table) {
            $table->foreignId('submission_status_id')->default(1)->after('user_id');
        });
    }

    public function down(): void {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('submission_status_id');
        });
    }
};
