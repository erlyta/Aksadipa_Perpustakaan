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
            if (!Schema::hasColumn('loans', 'return_date')) {
                $table->date('return_date')->nullable()->after('loan_date');
            }
            if (!Schema::hasColumn('loans', 'status')) {
                $table->string('status', 20)->default('dipinjam')->after('return_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            if (Schema::hasColumn('loans', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('loans', 'return_date')) {
                $table->dropColumn('return_date');
            }
        });
    }
};
