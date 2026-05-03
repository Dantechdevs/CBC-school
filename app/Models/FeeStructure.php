<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class FeeStructure extends Model {
    protected $fillable = ['name','grade_level','boarding_status','academic_year','term','tuition_fee','boarding_fee','activity_fee','exam_fee','other_fees','is_active'];
    protected $casts = ['is_active' => 'boolean', 'tuition_fee' => 'decimal:2', 'boarding_fee' => 'decimal:2', 'activity_fee' => 'decimal:2', 'exam_fee' => 'decimal:2', 'other_fees' => 'decimal:2'];
    public function getTotalAttribute(): float { return $this->tuition_fee + $this->boarding_fee + $this->activity_fee + $this->exam_fee + $this->other_fees; }
    public function invoices() { return $this->hasMany(FeeInvoice::class); }
}
