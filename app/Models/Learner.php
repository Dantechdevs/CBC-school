<?php

namespace App\Models;

use App\Enums\GradeLevel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Learner extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, LogsActivity;

    protected $fillable = [
        'admission_number', 'kemis_upi', 'first_name', 'middle_name', 'last_name',
        'date_of_birth', 'gender', 'grade_level', 'class_id', 'stream',
        'admission_date', 'boarding_status', 'special_needs', 'special_needs_details',
        'previous_school', 'birth_certificate_number', 'nhif_number',
        'is_active', 'academic_year',
    ];

    protected $casts = [
        'date_of_birth'   => 'date',
        'admission_date'  => 'date',
        'grade_level'     => GradeLevel::class,
        'is_active'       => 'boolean',
        'special_needs'   => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    // Relationships
    public function schoolClass() { return $this->belongsTo(SchoolClass::class, 'class_id'); }
    public function guardians()   { return $this->belongsToMany(Guardian::class, 'learner_guardian'); }
    public function assessments() { return $this->hasMany(Assessment::class); }
    public function feeInvoices() { return $this->hasMany(FeeInvoice::class); }
    public function examResults() { return $this->hasMany(ExamResult::class); }
    public function attendance()  { return $this->hasMany(Attendance::class); }
    public function portfolio()   { return $this->hasMany(Portfolio::class); }

    // Accessors
    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
    }

    public function getAgeAttribute(): int
    {
        return $this->date_of_birth->age;
    }

    public function usesRubric(): bool
    {
        return $this->grade_level?->usesRubric() ?? true;
    }

    // Scopes
    public function scopeActive($query)       { return $query->where('is_active', true); }
    public function scopeByGrade($query, $g)  { return $query->where('grade_level', $g); }
    public function scopeByYear($query, $y)   { return $query->where('academic_year', $y); }
}
