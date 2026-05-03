<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('kemis_sync_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('sync_type', ['learner', 'school', 'bulk_export']);
            $table->integer('records_synced')->default(0);
            $table->integer('records_failed')->default(0);
            $table->enum('status', ['running', 'completed', 'failed']);
            $table->text('error_log')->nullable();
            $table->foreignId('initiated_by')->constrained('staff_members');
            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('activity_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('log_name')->nullable()->index();
            $table->text('description');
            $table->nullableMorphs('subject', 'subject');
            $table->nullableMorphs('causer', 'causer');
            $table->json('properties')->nullable();
            $table->uuid('batch_uuid')->nullable();
            $table->string('event')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('activity_log');
        Schema::dropIfExists('kemis_sync_logs');
    }
};
