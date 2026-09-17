<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id', 'learning_area_id', 'strand_id', 'sub_strand_id',
        'grade_level', 'academic_year', 'term', 'week_number', 'lesson_number',
        'topic', 'objectives', 'materials_resources', 'learning_activities',
        'assessment_methods', 'status', 'approved_by', 'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function teacher()      { return $this->belongsTo(StaffMember::class, 'teacher_id'); }
    public function learningArea() { return $this->belongsTo(LearningArea::class); }
    public function strand()       { return $this->belongsTo(Strand::class); }
    public function subStrand()    { return $this->belongsTo(SubStrand::class); }
    public function approver()     { return $this->belongsTo(StaffMember::class, 'approved_by'); }

    public function scopeForTerm($q, $term, $year) { return $q->where('term', $term)->where('academic_year', $year); }
    public function scopePendingApproval($q)       { return $q->where('status', 'submitted'); }
}
