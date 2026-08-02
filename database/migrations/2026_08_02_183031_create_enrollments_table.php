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
    Schema::create('enrollments', function (Blueprint $table) {
        $table->id();

        $table->foreignId('student_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->foreignId('course_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->string('semester')->default('Fall');
        $table->unsignedSmallInteger('academic_year');
        $table->date('enrolled_at')->nullable();
        $table->timestamps();

        $table->unique(
            ['student_id', 'course_id', 'semester', 'academic_year'],
            'unique_student_course_enrollment'
        );
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
