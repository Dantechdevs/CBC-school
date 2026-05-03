<?php

namespace App\Models;

use App\Enums\GradeLevel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Learner extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'admission_number', 'kemis_upi', 'first_name', 'middle_name', 'last_name',
        'date_of_birth', 'gender', 'grade_level', 'class_id', 'stream',
        'admission_date', 'boarding_status', 'special_needs', 'special_needs_details',
        'previous_school', 'birth_certificate_number', 'nhif_number',
        'academic_year', 'is_active',
    ];

    protected $casts = [
        'date_of_birth'   => 'date',
        'admission_date'  => 'date',
        'special_needs'   => 'boolean',
        'is_active'       => 'boolean',
        'grade_level'     => GradeLevel::class,
    ];

    // ── Relationships ────────────────────────────────────────────
    public function schoolClass()   { return $this->belongsTo(SchoolClass::class, 'class_id'); }
    public function guardians()     { return $this->belongsToMany(Guardian::class, 'learner_guardian')->withPivot('is_primary'); }
    public function assessments()   { return $this->hasMany(Assessment::class); }
    public function attendance()    { return $this->hasMany(Attendance::class); }
    public function feeInvoices()   { return $this->hasMany(FeeInvoice::class); }
    public function feePayments()   { return $this->hasMany(FeePayment::class); }
    public function examResults()   { return $this->hasMany(ExamResult::class); }
    public function portfolio()     { return $this->hasMany(PortfolioItem::class); }

    // ── Accessors ────────────────────────────────────────────────
    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
    }

    public function getAgeAttribute(): int
    {
        return $this->date_of_birth?->age ?? 0;
    }

    public function getPrimaryGuardianAttribute(): ?Guardian
    {
        return $this->guardians->firstWhere('pivot.is_primary', true)
            ?? $this->guardians->first();
    }

    // ── Scopes ───────────────────────────────────────────────────
    public function scopeActive($q)             { return $q->where('is_active', true); }
    public function scopeForGrade($q, $grade)   { return $q->where('grade_level', $grade); }
    public function scopeForYear($q, $year)     { return $q->where('academic_year', $year); }
    public function scopeBoarding($q)           { return $q->where('boarding_status', 'boarding'); }
    public function scopeDay($q)                { return $q->where('boarding_status', 'day'); }
}
