<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Homework extends Model
{
    use HasFactory;

    protected $table = 'homework';

    protected $fillable = [
        'class_id', 'learning_area_id', 'sub_strand_id', 'teacher_id',
        'title', 'description', 'attachment_path',
        'academic_year', 'term', 'due_at', 'allow_late',
    ];

    protected $casts = [
        'due_at'     => 'datetime',
        'allow_late' => 'boolean',
    ];

    public function schoolClass()   { return $this->belongsTo(SchoolClass::class, 'class_id'); }
    public function learningArea()  { return $this->belongsTo(LearningArea::class); }
    public function subStrand()     { return $this->belongsTo(SubStrand::class); }
    public function teacher()       { return $this->belongsTo(StaffMember::class, 'teacher_id'); }
    public function submissions()   { return $this->hasMany(HomeworkSubmission::class); }

    public function scopeForTerm($q, $term, $year)
    {
        return $q->where('term', $term)->where('academic_year', $year);
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->due_at->isPast();
    }
}
