<?php

namespace App\Models;

use App\Enums\RubricLevel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeworkSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'homework_id', 'learner_id', 'submission_text', 'file_path',
        'status', 'score', 'max_score', 'rubric_level', 'feedback',
        'submitted_at', 'graded_by', 'graded_at',
    ];

    protected $casts = [
        'score'        => 'decimal:2',
        'max_score'    => 'decimal:2',
        'rubric_level' => RubricLevel::class,
        'submitted_at' => 'datetime',
        'graded_at'    => 'datetime',
    ];

    public function homework() { return $this->belongsTo(Homework::class); }
    public function learner()  { return $this->belongsTo(Learner::class); }
    public function grader()   { return $this->belongsTo(StaffMember::class, 'graded_by'); }

    public function scopeSubmitted($q) { return $q->whereIn('status', ['submitted', 'late', 'graded']); }
    public function scopeGraded($q)    { return $q->where('status', 'graded'); }
    public function scopePending($q)   { return $q->where('status', 'pending'); }
}
