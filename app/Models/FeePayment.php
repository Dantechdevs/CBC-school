<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'receipt_number','learner_id','fee_invoice_id','amount','payment_method',
        'transaction_reference','mpesa_receipt_number','mpesa_phone','status',
        'paid_at','received_by','notes',
    ];

    protected $casts = [
        'amount'   => 'decimal:2',
        'paid_at'  => 'datetime',
        'payment_method' => PaymentMethod::class,
    ];

    public function learner()    { return $this->belongsTo(Learner::class); }
    public function invoice()    { return $this->belongsTo(FeeInvoice::class, 'fee_invoice_id'); }
    public function receivedBy() { return $this->belongsTo(StaffMember::class, 'received_by'); }

    public function scopeConfirmed($q) { return $q->where('status', 'confirmed'); }
    public function scopeMpesa($q)     { return $q->where('payment_method', 'mpesa'); }
}
