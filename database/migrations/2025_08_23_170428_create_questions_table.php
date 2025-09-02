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
        Schema::create('questions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('template_id')->constrained('templates')->onDelete('cascade');
        $table->string('text', 255);
        $table->enum('type', ['file','text', 'number', 'date', 'select','radio']);
        $table->boolean('required')->default(false);
        $table->integer('order')->nullable();            
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
