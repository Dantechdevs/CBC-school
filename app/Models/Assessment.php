<?php

namespace App\Models;

use App\Enums\RubricLevel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'learner_id','learning_area_id','strand_id','sub_strand_id','teacher_id',
        'class_id','academic_year','term','assessment_type','rubric_level',
        'numeric_score','max_score','remarks','assessed_date',
    ];

    protected $casts = [
        'rubric_level'  => RubricLevel::class,
        'numeric_score' => 'decimal:2',
        'max_score'     => 'decimal:2',
        'assessed_date' => 'date',
    ];

    public function learner()      { return $this->belongsTo(Learner::class); }
    public function learningArea() { return $this->belongsTo(LearningArea::class); }
    public function strand()       { return $this->belongsTo(Strand::class); }
    public function subStrand()    { return $this->belongsTo(SubStrand::class); }
    public function teacher()      { return $this->belongsTo(StaffMember::class, 'teacher_id'); }
    public function schoolClass()  { return $this->belongsTo(SchoolClass::class, 'class_id'); }

    public function scopeFormative($q)  { return $q->where('assessment_type', 'formative'); }
    public function scopeSummative($q)  { return $q->where('assessment_type', 'summative'); }
    public function scopeForTerm($q, $term, $year) {
        return $q->where('term', $term)->where('academic_year', $year);
    }
}
