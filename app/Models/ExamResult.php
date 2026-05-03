<?php
namespace App\Models;
use App\Enums\RubricLevel;
use Illuminate\Database\Eloquent\Model;
class ExamResult extends Model {
    protected $fillable = ['exam_id','learner_id','marks_obtained','total_marks','grade','rubric_level','remarks','marked_by'];
    protected $casts = ['rubric_level' => RubricLevel::class, 'marks_obtained' => 'decimal:2', 'total_marks' => 'decimal:2'];
    public function exam()     { return $this->belongsTo(Exam::class); }
    public function learner()  { return $this->belongsTo(Learner::class); }
    public function markedBy() { return $this->belongsTo(StaffMember::class, 'marked_by'); }
    public function getPercentageAttribute(): float {
        return $this->total_marks > 0 ? round(($this->marks_obtained / $this->total_marks) * 100, 1) : 0;
    }
}
