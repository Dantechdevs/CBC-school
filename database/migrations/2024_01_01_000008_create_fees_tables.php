<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('fee_structures', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('grade_level');
            $table->enum('boarding_status', ['day', 'boarding', 'all'])->default('all');
            $table->string('academic_year', 9);
            $table->string('term');
            $table->decimal('tuition_fee', 10, 2)->default(0);
            $table->decimal('boarding_fee', 10, 2)->default(0);
            $table->decimal('activity_fee', 10, 2)->default(0);
            $table->decimal('exam_fee', 10, 2)->default(0);
            $table->decimal('other_fees', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('fee_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('learner_id')->constrained()->cascadeOnDelete();
            $table->foreignId('fee_structure_id')->constrained();
            $table->string('academic_year', 9);
            $table->string('term');
            $table->decimal('total_amount', 10, 2);
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->decimal('balance', 10, 2)->storedAs('total_amount - amount_paid');
            $table->enum('status', ['unpaid', 'partial', 'paid', 'waived', 'overpaid'])->default('unpaid');
            $table->date('due_date');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['learner_id', 'academic_year', 'term']);
        });

        Schema::create('fee_payments', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_number')->unique();
            $table->foreignId('learner_id')->constrained();
            $table->foreignId('fee_invoice_id')->constrained();
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['mpesa', 'bank', 'cash', 'bursary', 'waiver']);
            $table->string('transaction_reference')->nullable();
            $table->string('mpesa_receipt_number')->nullable();
            $table->string('mpesa_phone')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'failed', 'reversed'])->default('confirmed');
            $table->timestamp('paid_at');
            $table->foreignId('received_by')->nullable()->constrained('staff_members')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('bursaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learner_id')->constrained()->cascadeOnDelete();
            $table->string('source');
            $table->decimal('amount', 10, 2);
            $table->string('academic_year', 9);
            $table->string('term');
            $table->date('awarded_date');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('bursaries');
        Schema::dropIfExists('fee_payments');
        Schema::dropIfExists('fee_invoices');
        Schema::dropIfExists('fee_structures');
    }
};
