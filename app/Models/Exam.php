<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Exam extends Model {
    protected $fillable = ['name','grade_level','learning_area_id','academic_year','term','exam_type','total_marks','pass_mark','exam_date','start_time','duration_minutes','instructions','status','created_by'];
    protected $casts = ['exam_date' => 'date', 'total_marks' => 'decimal:2', 'pass_mark' => 'decimal:2'];
    public function learningArea() { return $this->belongsTo(LearningArea::class); }
    public function results()      { return $this->hasMany(ExamResult::class); }
    public function creator()      { return $this->belongsTo(StaffMember::class, 'created_by'); }
    public function isPublished(): bool { return $this->status === 'published'; }
}
