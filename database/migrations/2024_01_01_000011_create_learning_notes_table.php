<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('learning_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('staff_members');
            $table->foreignId('learning_area_id')->constrained();
            $table->foreignId('strand_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('sub_strand_id')->nullable()->constrained()->nullOnDelete();
            $table->string('grade_level');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('term');
            $table->string('academic_year', 9);
            $table->enum('resource_type', ['pdf', 'video', 'image', 'document', 'link', 'other'])->default('pdf');
            $table->string('file_path')->nullable();
            $table->string('external_url')->nullable();
            $table->boolean('is_published')->default(false);
            $table->integer('download_count')->default(0);
            $table->timestamps();
            $table->index(
                ['grade_level', 'learning_area_id', 'term', 'academic_year'],
                'ln_grade_area_term_year_index'
            );
        });
    }
    public function down(): void { Schema::dropIfExists('learning_notes'); }
};