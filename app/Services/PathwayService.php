<?php

namespace App\Services;

use App\Models\Assessment;
use Illuminate\Support\Collection;

class PathwayService
{
    /**
     * Compute per-pathway average performance (%) and total points for a learner in a given term.
     * Returns e.g. ['STEM' => ['percentage' => 60.4, 'points' => 28], ...]
     */
    public function forLearner(int $learnerId, string $term, string $academicYear): array
    {
        $assessments = Assessment::with('learningArea')
            ->where('learner_id', $learnerId)
            ->where('term', $term)
            ->where('academic_year', $academicYear)
            ->get()
            ->filter(fn($a) => $a->learningArea !== null);

        $pathways = config('school.pathways', []);
        $rubricValues = config('school.rubric_levels', []);
        $results = [];

        foreach ($pathways as $pathwayName => $keywords) {
            $matching = $assessments->filter(function ($assessment) use ($keywords) {
                $areaName = strtolower($assessment->learningArea->name);
                foreach ($keywords as $keyword) {
                    if (str_contains($areaName, strtolower($keyword))) {
                        return true;
                    }
                }
                return false;
            });

            $scores = $matching->map(function ($assessment) use ($rubricValues) {
                if ($assessment->numeric_score !== null) {
                    $max = $assessment->max_score ? (float) $assessment->max_score : 100;
                    return $max > 0 ? ((float) $assessment->numeric_score / $max) * 100 : null;
                }
                if ($assessment->rubric_level) {
                    $level = $assessment->rubric_level->value ?? $assessment->rubric_level;
                    $value = $rubricValues[$level]['value'] ?? null;
                    return $value ? ($value / 4) * 100 : null;
                }
                return null;
            })->filter(fn($s) => $s !== null);

            $results[$pathwayName] = [
                'percentage' => $scores->isNotEmpty() ? round($scores->avg(), 1) : null,
                'points'     => $scores->count(),
                'learning_areas' => $matching->pluck('learningArea.name')->unique()->values(),
            ];
        }

        return $results;
    }

    /**
     * Recommend a pathway based on which one the learner scores highest in.
     * Returns null if there isn't enough data yet.
     */
    public function recommend(int $learnerId, string $term, string $academicYear): ?string
    {
        $scores = collect($this->forLearner($learnerId, $term, $academicYear))
            ->filter(fn($p) => $p['percentage'] !== null);

        if ($scores->isEmpty()) {
            return null;
        }

        return $scores->sortByDesc('percentage')->keys()->first();
    }
}
