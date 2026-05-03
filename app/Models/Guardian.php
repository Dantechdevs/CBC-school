<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guardian extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name','last_name','phone_number','email','national_id',
        'relationship','occupation','physical_address','is_primary_contact','user_id',
    ];

    protected $casts = ['is_primary_contact' => 'boolean'];

    public function user()     { return $this->belongsTo(User::class); }
    public function learners() { return $this->belongsToMany(Learner::class, 'learner_guardian')->withPivot('is_primary'); }

    public function getFullNameAttribute(): string { return "{$this->first_name} {$this->last_name}"; }
}
