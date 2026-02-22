<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            if (!Schema::hasColumn('loans', 'late_days')) {
                $table->unsignedInteger('late_days')->default(0)->after('status');
            }

            if (!Schema::hasColumn('loans', 'fine_amount')) {
                $table->unsignedBigInteger('fine_amount')->default(0)->after('late_days');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            if (Schema::hasColumn('loans', 'fine_amount')) {
                $table->dropColumn('fine_amount');
            }

            if (Schema::hasColumn('loans', 'late_days')) {
                $table->dropColumn('late_days');
            }
        });
    }
};
