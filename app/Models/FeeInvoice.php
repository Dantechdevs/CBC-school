<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number','learner_id','fee_structure_id','academic_year',
        'term','total_amount','amount_paid','status','due_date','notes',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'amount_paid'  => 'decimal:2',
        'due_date'     => 'date',
    ];

    public function learner()      { return $this->belongsTo(Learner::class); }
    public function feeStructure() { return $this->belongsTo(FeeStructure::class); }
    public function payments()     { return $this->hasMany(FeePayment::class); }

    public function getBalanceAttribute(): float { return (float)$this->total_amount - (float)$this->amount_paid; }
    public function isPaid(): bool               { return $this->status === 'paid'; }
    public function isOverdue(): bool            { return $this->due_date->isPast() && !$this->isPaid(); }
}
