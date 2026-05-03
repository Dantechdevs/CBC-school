<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class InventoryTransaction extends Model {
    protected $fillable = ['item_id','type','quantity','balance_after','reference_number','learner_id','staff_id','processed_by','academic_year','remarks','transaction_date'];
    protected $casts = ['transaction_date' => 'date'];
    public function item()        { return $this->belongsTo(InventoryItem::class); }
    public function learner()     { return $this->belongsTo(Learner::class); }
    public function staff()       { return $this->belongsTo(StaffMember::class, 'staff_id'); }
    public function processedBy() { return $this->belongsTo(StaffMember::class, 'processed_by'); }
}
