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
       Schema::table('students', function (Blueprint $table) {
    $table->string('student_number')->unique()->after('id');
    $table->string('full_name');
    $table->string('email')->unique();
    $table->string('phone')->nullable();
    $table->string('major');
    $table->integer('academic_year');
    $table->enum('status', ['Active', 'Inactive'])->default('Active');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
    $table->dropColumn([
        'student_number',
        'full_name',
        'email',
        'phone',
        'major',
        'academic_year',
        'status',
    ]);
});
    }
};
