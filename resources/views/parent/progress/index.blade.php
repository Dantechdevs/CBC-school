@extends('layouts.parent')

@section('content')
@php
    $guardian = auth()->user()->guardian;
    $children = $guardian ? $guardian->learners()->where('is_active', true)->get() : collect();
    $term = config('school.current_term');
    $year = config('school.academic_year');
@endphp

<h2 class="text-xl font-bold text-gray-800 mb-6">Academic Progress</h2>

@if($children->isEmpty())
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center text-gray-400">
    No children are linked to your account yet.
</div>
@else
@foreach($children as $child)
@php
    $rubricCounts = \App\Models\Assessment::where('learner_id', $child->id)
        ->forTerm($term, $year)
        ->whereNotNull('rubric_level')
        ->selectRaw('rubric_level, count(*) as total')
        ->groupBy('rubric_level')
        ->pluck('total', 'rubric_level');

    $recentAssessments = \App\Models\Assessment::with('learningArea')
        ->where('learner_id', $child->id)
        ->forTerm($term, $year)
        ->latest('assessed_date')
        ->take(8)
        ->get();
@endphp
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-5">
    <h3 class="font-semibold text-gray-800 mb-4">{{ $child->full_name }} <span class="text-xs text-gray-400 font-normal">({{ $child->grade_level?->value }})</span></h3>

    <div class="grid grid-cols-4 gap-3 mb-4">
        @foreach([
            'EE' => ['Exceeds Expectation', 'green'],
            'ME' => ['Meets Expectation', 'blue'],
            'AE' => ['Approaches Expectation', 'yellow'],
            'BE' => ['Below Expectation', 'red'],
        ] as $code => [$label, $color])
        <div class="p-3 bg-{{ $color }}-50 rounded-lg border border-{{ $color }}-100 text-center">
            <p class="text-lg font-bold text-{{ $color }}-800">{{ $rubricCounts[$code] ?? 0 }}</p>
            <p class="text-xs text-{{ $color }}-600">{{ $code }}</p>
        </div>
        @endforeach
    </div>

    @if($recentAssessments->isEmpty())
    <p class="text-sm text-gray-400 text-center py-4">No assessments recorded this term yet.</p>
    @else
    <div class="space-y-1">
        @foreach($recentAssessments as $a)
        <div class="flex items-center justify-between border-b border-gray-50 py-1.5 last:border-0 text-sm">
            <span class="text-gray-700">{{ $a->learningArea->name ?? '—' }}</span>
            <span class="font-medium text-gray-500">{{ $a->assessed_date->format('d M') }}</span>
            @if($a->rubric_level)
            <span class="px-2 py-0.5 rounded text-xs font-bold bg-gray-100 text-gray-700">{{ $a->rubric_level->value }}</span>
            @endif
        </div>
        @endforeach
    </div>
    @endif
</div>
@endforeach
@endif
@endsection
