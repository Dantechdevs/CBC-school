<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffMember extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id','staff_number','tsc_number','first_name','last_name','email',
        'phone_number','gender','date_of_birth','employment_type','staff_type',
        'designation','department','date_joined','national_id','kra_pin',
        'nhif_number','nssf_number','basic_salary','qualifications','is_active',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'date_joined'   => 'date',
        'basic_salary'  => 'decimal:2',
        'is_active'     => 'boolean',
    ];

    public function user()         { return $this->belongsTo(User::class); }
    public function classes()      { return $this->hasMany(SchoolClass::class, 'class_teacher_id'); }
    public function assessments()  { return $this->hasMany(Assessment::class, 'teacher_id'); }
    public function timetableSlots(){ return $this->hasMany(TimetableSlot::class, 'teacher_id'); }
    public function lessonPlans()  { return $this->hasMany(LessonPlan::class, 'teacher_id'); }

    public function getFullNameAttribute(): string { return "{$this->first_name} {$this->last_name}"; }

    public function scopeActive($q)      { return $q->where('is_active', true); }
    public function scopeTeaching($q)    { return $q->where('staff_type', 'teaching'); }
    public function scopeNonTeaching($q) { return $q->where('staff_type', 'non_teaching'); }
}
