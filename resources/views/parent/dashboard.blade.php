@extends('layouts.parent')

@section('content')
@php
    $guardian = auth()->user()->guardian;
    $children = $guardian ? $guardian->learners()->where('is_active', true)->get() : collect();
    $term = config('school.current_term');
    $year = config('school.academic_year');

    $childrenData = $children->mapWithKeys(function ($child) use ($term, $year) {
        $balance = \App\Models\FeeInvoice::where('learner_id', $child->id)
            ->where('term', $term)->where('academic_year', $year)
            ->selectRaw('SUM(total_amount - amount_paid) as balance')->value('balance') ?? 0;

        $pendingHomework = \App\Models\Homework::where('class_id', $child->class_id)
            ->forTerm($term, $year)
            ->whereDoesntHave('submissions', fn($q) => $q->where('learner_id', $child->id))
            ->count();

        return [$child->id => ['balance' => $balance, 'pendingHomework' => $pendingHomework]];
    });
@endphp

<h2 class="text-xl font-bold text-gray-800 mb-6">Welcome, {{ $guardian?->full_name ?? auth()->user()->name }}</h2>

@if($children->isEmpty())
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center text-gray-400">
    No children are linked to your account yet. Please contact the school office.
</div>
@else
<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    @foreach($children as $child)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center font-bold text-green-700">
                {{ strtoupper(substr($child->first_name, 0, 1)) }}
            </div>
            <div>
                <p class="font-semibold text-gray-800">{{ $child->full_name }}</p>
                <p class="text-xs text-gray-400">{{ $child->grade_level?->value }} · {{ $child->admission_number }}</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3 text-sm mb-4">
            <div class="bg-gray-50 rounded-lg p-3">
                <p class="text-xs text-gray-400">Fee Balance</p>
                <p class="font-bold {{ $childrenData[$child->id]['balance'] > 0 ? 'text-red-700' : 'text-green-700' }}">
                    KES {{ number_format($childrenData[$child->id]['balance']) }}
                </p>
            </div>
            <div class="bg-gray-50 rounded-lg p-3">
                <p class="text-xs text-gray-400">Pending Homework</p>
                <p class="font-bold text-gray-800">{{ $childrenData[$child->id]['pendingHomework'] }}</p>
            </div>
        </div>
        <div class="flex gap-2 text-xs">
            <a href="{{ route('parent.progress.index') }}" class="flex-1 text-center bg-blue-50 text-blue-700 py-2 rounded-lg font-medium hover:bg-blue-100">Progress</a>
            <a href="{{ route('parent.fees.index') }}" class="flex-1 text-center bg-yellow-50 text-yellow-700 py-2 rounded-lg font-medium hover:bg-yellow-100">Fees</a>
            <a href="{{ route('parent.homework.index') }}" class="flex-1 text-center bg-purple-50 text-purple-700 py-2 rounded-lg font-medium hover:bg-purple-100">Homework</a>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection
