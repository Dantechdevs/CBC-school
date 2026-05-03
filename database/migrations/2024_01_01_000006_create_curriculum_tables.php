<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('learning_areas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('grade_level');
            $table->string('color', 7)->default('#3B82F6');
            $table->integer('weekly_lessons')->default(5);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('strands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learning_area_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('sub_strands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('strand_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('specific_learning_outcomes')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('lesson_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('staff_members');
            $table->foreignId('learning_area_id')->constrained();
            $table->foreignId('strand_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('sub_strand_id')->nullable()->constrained()->nullOnDelete();
            $table->string('grade_level');
            $table->string('academic_year', 9);
            $table->string('term');
            $table->integer('week_number');
            $table->integer('lesson_number');
            $table->string('topic');
            $table->text('objectives');
            $table->text('materials_resources')->nullable();
            $table->text('learning_activities')->nullable();
            $table->text('assessment_methods')->nullable();
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft');
            $table->foreignId('approved_by')->nullable()->constrained('staff_members')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('lesson_plans');
        Schema::dropIfExists('sub_strands');
        Schema::dropIfExists('strands');
        Schema::dropIfExists('learning_areas');
    }
};
