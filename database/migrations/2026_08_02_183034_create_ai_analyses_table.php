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
    Schema::create('ai_analyses', function (Blueprint $table) {
        $table->id();

        $table->foreignId('student_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->string('analysis_type')->default('performance');
        $table->text('input_summary')->nullable();
        $table->longText('analysis');
        $table->longText('recommendations')->nullable();
        $table->string('provider')->nullable();
        $table->string('model')->nullable();
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_analyses');
    }
};
