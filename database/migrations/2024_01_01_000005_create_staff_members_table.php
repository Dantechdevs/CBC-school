<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('staff_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('staff_number')->unique();
            $table->string('tsc_number')->unique()->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone_number');
            $table->enum('gender', ['male', 'female']);
            $table->date('date_of_birth')->nullable();
            $table->enum('employment_type', ['permanent', 'contract', 'bom', 'volunteer'])->default('permanent');
            $table->enum('staff_type', ['teaching', 'non_teaching'])->default('teaching');
            $table->string('designation')->nullable();
            $table->string('department')->nullable();
            $table->date('date_joined');
            $table->string('national_id')->nullable();
            $table->string('kra_pin')->nullable();
            $table->string('nhif_number')->nullable();
            $table->string('nssf_number')->nullable();
            $table->decimal('basic_salary', 10, 2)->default(0);
            $table->text('qualifications')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('staff_members'); }
};
