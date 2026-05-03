<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class LearningArea extends Model {
    use HasFactory;
    protected $fillable = ['name','code','grade_level','color','weekly_lessons','is_active'];
    protected $casts = ['is_active' => 'boolean'];
    public function strands()      { return $this->hasMany(Strand::class); }
    public function assessments()  { return $this->hasMany(Assessment::class); }
    public function notes()        { return $this->hasMany(LearningNote::class); }
    public function timetableSlots() { return $this->hasMany(TimetableSlot::class); }
    public function scopeForGrade($q, $grade) { return $q->where('grade_level', $grade); }
    public function scopeActive($q) { return $q->where('is_active', true); }
}
