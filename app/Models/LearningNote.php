<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id','learning_area_id','strand_id','sub_strand_id','grade_level',
        'title','description','term','academic_year','resource_type',
        'file_path','external_url','is_published','download_count',
    ];

    protected $casts = ['is_published' => 'boolean'];

    public function teacher()      { return $this->belongsTo(StaffMember::class, 'teacher_id'); }
    public function learningArea() { return $this->belongsTo(LearningArea::class); }
    public function strand()       { return $this->belongsTo(Strand::class); }
    public function subStrand()    { return $this->belongsTo(SubStrand::class); }

    public function scopePublished($q) { return $q->where('is_published', true); }
    public function scopeForGrade($q, $grade) { return $q->where('grade_level', $grade); }
}
