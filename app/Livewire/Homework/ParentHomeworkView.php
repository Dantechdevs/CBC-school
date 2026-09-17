<?php

namespace App\Livewire\Homework;

use App\Models\Homework;
use Livewire\Component;

class ParentHomeworkView extends Component
{
    public function render()
    {
        $guardian = auth()->user()->guardian;
        $children = $guardian ? $guardian->learners()->where('is_active', true)->get() : collect();

        $childHomework = $children->mapWithKeys(function ($child) {
            $homework = Homework::with(['learningArea', 'schoolClass'])
                ->where('class_id', $child->class_id)
                ->forTerm(config('school.current_term'), config('school.academic_year'))
                ->with(['submissions' => fn($q) => $q->where('learner_id', $child->id)])
                ->latest('due_at')
                ->get();

            return [$child->id => $homework];
        });

        return view('livewire.homework.parent-homework-view', [
            'children'      => $children,
            'childHomework' => $childHomework,
        ])->layout('layouts.parent', ['header' => 'Homework']);
    }
}
