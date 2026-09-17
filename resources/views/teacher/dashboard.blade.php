@extends('layouts.teacher')

@section('header', 'Teacher Dashboard')

@section('content')
@php
    $staff = auth()->user()->staffMember;
    $term = config('school.current_term');
    $year = config('school.academic_year');

    $myAssessmentsThisTerm = $staff ? \App\Models\Assessment::where('teacher_id', $staff->id)->forTerm($term, $year)->count() : 0;
    $myHomework = $staff ? \App\Models\Homework::where('teacher_id', $staff->id)->forTerm($term, $year)->withCount('submissions')->latest('due_at')->take(5)->get() : collect();
    $myLessonPlans = $staff ? \App\Models\LessonPlan::where('teacher_id', $staff->id)->forTerm($term, $year)->latest()->take(5)->get() : collect();
    $draftLessonPlans = $staff ? \App\Models\LessonPlan::where('teacher_id', $staff->id)->where('status', 'draft')->count() : 0;
    $todaySlots = \App\Models\TimetableSlot::with(['schoolClass','learningArea'])
        ->where('teacher_id', $staff?->id)
        ->where('day_of_week', strtolower(now()->format('l')))
        ->where('is_active', true)
        ->orderBy('start_time')
        ->get();
@endphp

@if(!$staff)
<div class="bg-yellow-50 border border-yellow-200 text-yellow-800 text-sm rounded-lg px-4 py-3 mb-6">
    Your account is not linked to a staff record, so per-teacher stats can't be shown. Contact a super-admin to link your profile.
</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Assessments This Term</p>
        <p class="text-2xl font-bold text-gray-900">{{ $myAssessmentsThisTerm }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Homework Assigned</p>
        <p class="text-2xl font-bold text-blue-700">{{ $myHomework->count() }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Draft Lesson Plans</p>
        <p class="text-2xl font-bold text-yellow-700">{{ $draftLessonPlans }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Lessons Today</p>
        <p class="text-2xl font-bold text-gray-900">{{ $todaySlots->count() }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800">Today's Timetable ({{ now()->format('l') }})</h3>
            <a href="{{ route('teacher.timetable.index') }}" class="text-xs text-blue-700 hover:underline">Full timetable →</a>
        </div>
        @if($todaySlots->isEmpty())
        <p class="text-sm text-gray-400 text-center py-6">No lessons scheduled for you today.</p>
        @else
        <div class="space-y-2">
            @foreach($todaySlots as $slot)
            <div class="flex items-center justify-between border-b border-gray-50 pb-2 last:border-0">
                <div>
                    <p class="text-sm font-medium text-gray-800">{{ $slot->learningArea->name ?? 'Unassigned' }}</p>
                    <p class="text-xs text-gray-400">{{ $slot->schoolClass->name ?? '' }}</p>
                </div>
                <span class="text-xs text-gray-500">{{ \Illuminate\Support\Carbon::parse($slot->start_time)->format('H:i') }}</span>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800">My Homework</h3>
            <a href="{{ route('teacher.homework.index') }}" class="text-xs text-blue-700 hover:underline">Manage →</a>
        </div>
        @if($myHomework->isEmpty())
        <p class="text-sm text-gray-400 text-center py-6">You haven't assigned any homework this term.</p>
        @else
        <div class="space-y-2">
            @foreach($myHomework as $hw)
            <div class="flex items-center justify-between border-b border-gray-50 pb-2 last:border-0">
                <div>
                    <p class="text-sm font-medium text-gray-800">{{ $hw->title }}</p>
                    <p class="text-xs text-gray-400">due {{ $hw->due_at->format('d M') }}</p>
                </div>
                <span class="text-xs font-medium text-gray-600">{{ $hw->submissions_count }} submitted</span>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

<div class="grid grid-cols-2 md:grid-cols-5 gap-3">
    <a href="{{ route('teacher.assessment.index') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center hover:bg-blue-50">
        <p class="text-xs font-medium text-blue-800">Enter Assessment</p>
    </a>
    <a href="{{ route('teacher.homework.index') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center hover:bg-green-50">
        <p class="text-xs font-medium text-green-800">Assign Homework</p>
    </a>
    <a href="{{ route('teacher.attendance.index') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center hover:bg-purple-50">
        <p class="text-xs font-medium text-purple-800">Mark Attendance</p>
    </a>
    <a href="{{ route('teacher.notes.index') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center hover:bg-yellow-50">
        <p class="text-xs font-medium text-yellow-800">Learning Notes</p>
    </a>
    <a href="{{ route('teacher.timetable.index') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center hover:bg-red-50">
        <p class="text-xs font-medium text-red-800">Timetable</p>
    </a>
</div>
@endsection
