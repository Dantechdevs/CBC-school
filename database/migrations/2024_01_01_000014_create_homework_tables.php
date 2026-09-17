<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('homework', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('school_classes');
            $table->foreignId('learning_area_id')->constrained();
            $table->foreignId('sub_strand_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('teacher_id')->constrained('staff_members');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('attachment_path')->nullable();
            $table->string('academic_year', 9);
            $table->string('term');
            $table->dateTime('due_at');
            $table->boolean('allow_late')->default(false);
            $table->timestamps();
            $table->index(['class_id', 'term', 'academic_year']);
        });

        Schema::create('homework_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('homework_id')->constrained()->cascadeOnDelete();
            $table->foreignId('learner_id')->constrained()->cascadeOnDelete();
            $table->text('submission_text')->nullable();
            $table->string('file_path')->nullable();
            $table->enum('status', ['pending', 'submitted', 'late', 'graded'])->default('pending');
            $table->decimal('score', 6, 2)->nullable();
            $table->decimal('max_score', 6, 2)->nullable();
            $table->enum('rubric_level', ['EE', 'ME', 'AE', 'BE'])->nullable();
            $table->text('feedback')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->foreignId('graded_by')->nullable()->constrained('staff_members')->nullOnDelete();
            $table->timestamp('graded_at')->nullable();
            $table->timestamps();
            $table->unique(['homework_id', 'learner_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('homework_submissions');
        Schema::dropIfExists('homework');
    }
};
