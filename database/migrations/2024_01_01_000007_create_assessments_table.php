<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learner_id')->constrained()->cascadeOnDelete();
            $table->foreignId('learning_area_id')->constrained();
            $table->foreignId('strand_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('sub_strand_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('teacher_id')->constrained('staff_members');
            $table->foreignId('class_id')->constrained('school_classes');
            $table->string('academic_year', 9);
            $table->string('term');
            $table->enum('assessment_type', ['formative', 'summative'])->default('formative');
            $table->enum('rubric_level', ['EE', 'ME', 'AE', 'BE'])->nullable();
            $table->decimal('numeric_score', 6, 2)->nullable();
            $table->decimal('max_score', 6, 2)->nullable();
            $table->text('remarks')->nullable();
            $table->date('assessed_date');
            $table->timestamps();
            $table->index(['learner_id', 'learning_area_id', 'term', 'academic_year']);
        });

        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learner_id')->constrained()->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('school_classes');
            $table->date('date');
            $table->enum('status', ['present', 'absent', 'late', 'excused']);
            $table->string('session')->default('full_day');
            $table->text('remarks')->nullable();
            $table->foreignId('recorded_by')->constrained('staff_members');
            $table->timestamps();
            $table->unique(['learner_id', 'date', 'session']);
        });

        Schema::create('portfolio_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learner_id')->constrained()->cascadeOnDelete();
            $table->foreignId('learning_area_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('item_type')->default('project');
            $table->string('term');
            $table->string('academic_year', 9);
            $table->foreignId('uploaded_by')->constrained('staff_members');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('portfolio_items');
        Schema::dropIfExists('attendance');
        Schema::dropIfExists('assessments');
    }
};
