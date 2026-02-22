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
        Schema::create('books', function (Blueprint $table) {
    $table->id();
    $table->string('title',150);
    $table->string('author',100);
    $table->string('publisher',100);
    $table->year('year');
    $table->unsignedBigInteger('category_id')->nullable();
    $table->integer('stock')->default(0);
    $table->text('synopsis')->nullable();
    $table->string('cover')->nullable();
    $table->string('file_pdf')->nullable();
    $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));

    $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
