<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('inventory_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->default('general');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('inventory_categories');
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->string('unit')->default('piece');
            $table->integer('quantity_in_stock')->default(0);
            $table->integer('minimum_stock_level')->default(5);
            $table->integer('quantity_issued')->default(0);
            $table->decimal('unit_cost', 10, 2)->default(0);
            $table->string('supplier')->nullable();
            $table->string('grade_level')->nullable();
            $table->boolean('is_textbook')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('inventory_items');
            $table->enum('type', ['received', 'issued', 'returned', 'disposed', 'adjusted', 'transferred']);
            $table->integer('quantity');
            $table->integer('balance_after');
            $table->string('reference_number')->nullable();
            $table->foreignId('learner_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('staff_id')->nullable()->constrained('staff_members')->nullOnDelete();
            $table->foreignId('processed_by')->constrained('staff_members');
            $table->string('academic_year', 9)->nullable();
            $table->text('remarks')->nullable();
            $table->date('transaction_date');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('inventory_transactions');
        Schema::dropIfExists('inventory_items');
        Schema::dropIfExists('inventory_categories');
    }
};
