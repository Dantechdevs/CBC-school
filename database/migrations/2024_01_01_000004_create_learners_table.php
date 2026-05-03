<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('learners', function (Blueprint $table) {
            $table->id();
            $table->string('admission_number')->unique();
            $table->string('kemis_upi')->unique()->nullable();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female']);
            $table->string('grade_level');
            $table->foreignId('class_id')->constrained('school_classes')->cascadeOnDelete();
            $table->string('stream')->nullable();
            $table->date('admission_date');
            $table->enum('boarding_status', ['day', 'boarding'])->default('day');
            $table->boolean('special_needs')->default(false);
            $table->text('special_needs_details')->nullable();
            $table->string('previous_school')->nullable();
            $table->string('birth_certificate_number')->nullable();
            $table->string('nhif_number')->nullable();
            $table->string('academic_year', 9);
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
            $table->index(['grade_level', 'academic_year']);
            $table->index(['class_id', 'is_active']);
        });

        Schema::create('guardians', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone_number');
            $table->string('email')->nullable();
            $table->string('national_id')->nullable();
            $table->enum('relationship', ['father', 'mother', 'guardian', 'other']);
            $table->string('occupation')->nullable();
            $table->text('physical_address')->nullable();
            $table->boolean('is_primary_contact')->default(false);
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('learner_guardian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learner_id')->constrained()->cascadeOnDelete();
            $table->foreignId('guardian_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
            $table->unique(['learner_id', 'guardian_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('learner_guardian');
        Schema::dropIfExists('guardians');
        Schema::dropIfExists('learners');
    }
};
