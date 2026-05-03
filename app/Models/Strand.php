<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Strand extends Model {
    protected $fillable = ['learning_area_id','name','description','order'];
    public function learningArea() { return $this->belongsTo(LearningArea::class); }
    public function subStrands()   { return $this->hasMany(SubStrand::class)->orderBy('order'); }
    public function assessments()  { return $this->hasMany(Assessment::class); }
}
