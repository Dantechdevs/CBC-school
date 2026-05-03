<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('grade_level');
            $table->foreignId('learning_area_id')->constrained();
            $table->string('academic_year', 9);
            $table->string('term');
            $table->enum('exam_type', ['cat', 'mid_term', 'end_term', 'mock', 'kpsea', 'kcse'])->default('end_term');
            $table->decimal('total_marks', 6, 2)->default(100);
            $table->decimal('pass_mark', 6, 2)->default(50);
            $table->date('exam_date')->nullable();
            $table->time('start_time')->nullable();
            $table->integer('duration_minutes')->default(120);
            $table->text('instructions')->nullable();
            $table->enum('status', ['draft', 'published', 'completed', 'cancelled'])->default('draft');
            $table->foreignId('created_by')->constrained('staff_members');
            $table->timestamps();
        });

        Schema::create('exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
            $table->foreignId('learner_id')->constrained()->cascadeOnDelete();
            $table->decimal('marks_obtained', 6, 2)->nullable();
            $table->decimal('total_marks', 6, 2);
            $table->string('grade')->nullable();
            $table->enum('rubric_level', ['EE', 'ME', 'AE', 'BE'])->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('marked_by')->constrained('staff_members');
            $table->timestamps();
            $table->unique(['exam_id', 'learner_id']);
        });

        Schema::create('exam_timetable', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('school_classes');
            $table->foreignId('invigilator_id')->constrained('staff_members');
            $table->string('venue')->nullable();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('exam_timetable');
        Schema::dropIfExists('exam_results');
        Schema::dropIfExists('exams');
    }
};
