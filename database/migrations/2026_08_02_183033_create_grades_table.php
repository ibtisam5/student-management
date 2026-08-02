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
    Schema::create('grades', function (Blueprint $table) {
        $table->id();

        $table->foreignId('enrollment_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->string('assessment_name');

        $table->enum('assessment_type', [
            'Quiz',
            'Assignment',
            'Midterm',
            'Final',
            'Project',
            'Other',
        ])->default('Other');

        $table->decimal('score', 6, 2);
        $table->decimal('maximum_score', 6, 2)->default(100);
        $table->decimal('weight', 5, 2)->default(0);
        $table->date('assessment_date')->nullable();
        $table->text('notes')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
