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
        Schema::create('responses', function (Blueprint $table) {
        $table->id();
        $table->foreignId('complaint_id')->constrained('complaints')->onDelete('cascade');
        $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
        $table->text('text_response')->nullable();
        $table->foreignId('response_option_id')->nullable()->constrained('options_questions')->onDelete('set null');            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('response');
    }
};
