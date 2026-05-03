<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class SubStrand extends Model {
    protected $fillable = ['strand_id','name','specific_learning_outcomes','order'];
    public function strand() { return $this->belongsTo(Strand::class); }
    public function assessments() { return $this->hasMany(Assessment::class); }
}
