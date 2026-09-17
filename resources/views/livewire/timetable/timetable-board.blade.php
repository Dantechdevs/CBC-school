<div>
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">Timetable</h2>
        <span class="text-sm text-gray-500 bg-green-50 px-3 py-1 rounded-full border border-green-200">
            Term {{ $term }}, {{ $academicYear }}
        </span>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4 mb-5 flex flex-wrap gap-4 items-center">
        <label class="text-sm text-gray-600 font-medium">Class:</label>
        <select wire:model.live="classFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm min-w-56">
            <option value="">Select a class...</option>
            @foreach($classes as $class)
                <option value="{{ $class->id }}">{{ $class->name }} ({{ $class->grade_level }}{{ $class->stream ? ' - '.$class->stream : '' }})</option>
            @endforeach
        </select>
    </div>

    @if(!$classFilter)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
        <svg class="mx-auto h-10 w-10 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        <p class="text-sm text-gray-400">Select a class to view its timetable.</p>
    </div>
    @elseif($slots->isEmpty())
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
        <svg class="mx-auto h-10 w-10 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        <p class="text-sm text-gray-400">No timetable slots have been scheduled for this class yet.</p>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        @foreach($days as $dayKey => $dayLabel)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-green-800 text-white text-sm font-semibold px-3 py-2">{{ $dayLabel }}</div>
            <div class="p-2 space-y-2 min-h-24">
                @forelse($slots->get($dayKey, collect()) as $slot)
                <div class="bg-green-50 border border-green-100 rounded-lg p-2">
                    <p class="text-xs font-semibold text-green-800">{{ $slot->learningArea->name ?? 'Unassigned' }}</p>
                    <p class="text-xs text-gray-500">{{ \Illuminate\Support\Carbon::parse($slot->start_time)->format('H:i') }} – {{ \Illuminate\Support\Carbon::parse($slot->end_time)->format('H:i') }}</p>
                    <p class="text-xs text-gray-400">{{ $slot->teacher->full_name ?? 'TBA' }}</p>
                    @if($slot->venue)<p class="text-xs text-gray-400">📍 {{ $slot->venue }}</p>@endif
                </div>
                @empty
                <p class="text-xs text-gray-300 italic px-1">No lessons</p>
                @endforelse
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
