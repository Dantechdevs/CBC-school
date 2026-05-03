<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('school_classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('grade_level');
            $table->string('stream')->nullable();
            $table->string('academic_year', 9);
            $table->unsignedBigInteger('class_teacher_id')->nullable();
            $table->integer('capacity')->default(45);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['grade_level', 'academic_year']);
        });
    }
    public function down(): void { Schema::dropIfExists('school_classes'); }
};
