<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('timetable_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('school_classes')->cascadeOnDelete();
            $table->foreignId('learning_area_id')->constrained();
            $table->foreignId('teacher_id')->constrained('staff_members');
            $table->enum('day_of_week', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday']);
            $table->time('start_time');
            $table->time('end_time');
            $table->string('venue')->nullable();
            $table->string('academic_year', 9);
            $table->string('term');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['class_id', 'day_of_week', 'academic_year', 'term']);
        });

        Schema::create('school_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('staff_members');
            $table->string('title');
            $table->text('message');
            $table->enum('type', ['general', 'fees', 'exam', 'report_card', 'attendance', 'emergency'])->default('general');
            $table->enum('channel', ['sms', 'email', 'push', 'all'])->default('all');
            $table->json('target_recipients')->nullable();
            $table->string('target_grade')->nullable();
            $table->integer('total_recipients')->default(0);
            $table->integer('sent_count')->default(0);
            $table->integer('failed_count')->default(0);
            $table->enum('status', ['draft', 'queued', 'sent', 'failed', 'partial'])->default('draft');
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });

        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notification_id')->constrained('school_notifications')->cascadeOnDelete();
            $table->string('recipient_phone')->nullable();
            $table->string('recipient_email')->nullable();
            $table->enum('channel', ['sms', 'email', 'push']);
            $table->enum('status', ['sent', 'delivered', 'failed', 'pending']);
            $table->string('provider_message_id')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('notification_logs');
        Schema::dropIfExists('school_notifications');
        Schema::dropIfExists('timetable_slots');
    }
};
