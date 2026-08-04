<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ai_analyses', function (Blueprint $table) {
            $table->text('performance_summary')->nullable();
            $table->json('strengths')->nullable();
            $table->json('weaknesses')->nullable();
            $table->string('risk_level', 30)->nullable();
            $table->text('prediction')->nullable();
            $table->json('metrics')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('ai_analyses', function (Blueprint $table) {
            $table->dropColumn([
                'performance_summary',
                'strengths',
                'weaknesses',
                'risk_level',
                'prediction',
                'metrics',
            ]);
        });
    }
};
