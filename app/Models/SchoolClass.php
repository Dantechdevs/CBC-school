<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class SchoolClass extends Model {
    use HasFactory;
    protected $fillable = ['name','grade_level','stream','academic_year','class_teacher_id','capacity','is_active'];
    protected $casts = ['is_active' => 'boolean'];
    public function classTeacher() { return $this->belongsTo(StaffMember::class, 'class_teacher_id'); }
    public function learners()     { return $this->hasMany(Learner::class, 'class_id'); }
    public function timetable()    { return $this->hasMany(TimetableSlot::class, 'class_id'); }
    public function assessments()  { return $this->hasMany(Assessment::class, 'class_id'); }
    public function scopeActive($q) { return $q->where('is_active', true); }
    public function scopeForYear($q, $y) { return $q->where('academic_year', $y); }
}
