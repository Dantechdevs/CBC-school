@extends('layouts.parent')

@section('content')
@php
    $guardian = auth()->user()->guardian;
    $children = $guardian ? $guardian->learners()->where('is_active', true)->get() : collect();
    $gradeLevels = $children->pluck('grade_level')->map(fn($g) => $g?->value)->filter()->unique();

    $notes = \App\Models\LearningNote::with('learningArea')
        ->published()
        ->whereIn('grade_level', $gradeLevels)
        ->latest()
        ->get()
        ->groupBy('grade_level');
@endphp

<h2 class="text-xl font-bold text-gray-800 mb-6">Learning Notes</h2>

@if($children->isEmpty())
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center text-gray-400">
    No children are linked to your account yet.
</div>
@elseif($notes->isEmpty())
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center text-gray-400">
    No learning notes have been published for your children's grades yet.
</div>
@else
@foreach($notes as $grade => $gradeNotes)
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-5">
    <h3 class="font-semibold text-gray-800 mb-3">{{ $grade }}</h3>
    <div class="space-y-2">
        @foreach($gradeNotes as $note)
        <div class="flex items-center justify-between border-b border-gray-50 py-2 last:border-0">
            <div>
                <p class="text-sm font-medium text-gray-800">{{ $note->title }}</p>
                <p class="text-xs text-gray-400">{{ $note->learningArea->name ?? '' }} · {{ ucfirst($note->resource_type) }}</p>
            </div>
            @if($note->file_path)
            <a href="{{ \Illuminate\Support\Facades\Storage::url($note->file_path) }}" target="_blank" class="text-xs font-medium text-blue-600 hover:text-blue-800">Download</a>
            @elseif($note->external_url)
            <a href="{{ $note->external_url }}" target="_blank" class="text-xs font-medium text-blue-600 hover:text-blue-800">Open Link</a>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endforeach
@endif
@endsection
