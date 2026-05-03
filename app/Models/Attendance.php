<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Attendance extends Model {
    protected $fillable = ['learner_id','class_id','date','status','session','remarks','recorded_by'];
    protected $casts = ['date' => 'date'];
    public function learner()    { return $this->belongsTo(Learner::class); }
    public function schoolClass(){ return $this->belongsTo(SchoolClass::class, 'class_id'); }
    public function recorder()   { return $this->belongsTo(StaffMember::class, 'recorded_by'); }
}
