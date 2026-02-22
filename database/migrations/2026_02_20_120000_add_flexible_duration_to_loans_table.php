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
            if (!Schema::hasColumn('loans', 'duration_unit')) {
                $table->string('duration_unit', 10)->default('day')->after('duration_days');
            }

            if (!Schema::hasColumn('loans', 'duration_value')) {
                $table->unsignedInteger('duration_value')->nullable()->after('duration_unit');
            }

            if (!Schema::hasColumn('loans', 'loan_start_at')) {
                $table->dateTime('loan_start_at')->nullable()->after('loan_date');
            }

            if (!Schema::hasColumn('loans', 'due_at')) {
                $table->dateTime('due_at')->nullable()->after('return_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            if (Schema::hasColumn('loans', 'due_at')) {
                $table->dropColumn('due_at');
            }

            if (Schema::hasColumn('loans', 'loan_start_at')) {
                $table->dropColumn('loan_start_at');
            }

            if (Schema::hasColumn('loans', 'duration_value')) {
                $table->dropColumn('duration_value');
            }

            if (Schema::hasColumn('loans', 'duration_unit')) {
                $table->dropColumn('duration_unit');
            }
        });
    }
};
