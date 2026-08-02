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
    Schema::create('attendances', function (Blueprint $table) {
        $table->id();

        $table->foreignId('enrollment_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->date('attendance_date');

        $table->enum('status', [
            'Present',
            'Absent',
            'Late',
            'Excused',
        ])->default('Present');

        $table->text('notes')->nullable();
        $table->timestamps();

        $table->unique(
            ['enrollment_id', 'attendance_date'],
            'unique_enrollment_attendance'
        );
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
