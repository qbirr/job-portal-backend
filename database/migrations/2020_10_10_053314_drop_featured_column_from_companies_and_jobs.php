<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobs', function (Blueprint $table) {
            if (Schema::hasColumn('jobs', 'is_featured')) {
                $table->dropColumn('is_featured');
            }
        });

        Schema::table('companies', function (Blueprint $table) {
            if (Schema::hasColumn('jobs', 'is_featured')) {
                $table->dropColumn('is_featured');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies_and_jobs', function (Blueprint $table) {
            //
        });
    }
};
