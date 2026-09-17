<div>
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">Mark Attendance</h2>
    </div>

    @if($flashMessage)
    <div class="bg-green-50 border border-green-200 text-green-800 text-sm rounded-lg px-4 py-3 mb-5">
        {{ $flashMessage }}
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm p-4 mb-5 flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Class</label>
            <select wire:model.live="classId" class="border border-gray-300 rounded-lg px-3 py-2 text-sm min-w-48">
                <option value="">Select class...</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Date</label>
            <input wire:model.live="date" type="date" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Session</label>
            <select wire:model.live="session" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="full_day">Full Day</option>
                <option value="morning">Morning</option>
                <option value="afternoon">Afternoon</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button wire:click="markAll('present')" class="px-3 py-2 text-xs font-medium bg-green-100 text-green-700 rounded-lg hover:bg-green-200">Mark All Present</button>
            <button wire:click="markAll('absent')" class="px-3 py-2 text-xs font-medium bg-red-100 text-red-700 rounded-lg hover:bg-red-200">Mark All Absent</button>
        </div>
    </div>

    @if(empty($records))
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center text-gray-400">
        Select a class to mark attendance.
    </div>
    @else
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Learner</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @foreach($learners as $learner)
                <tr>
                    <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ $learner->full_name }}</td>
                    <td class="px-4 py-3">
                        <div class="flex gap-1">
                            @foreach(['present' => 'green', 'late' => 'yellow', 'excused' => 'blue', 'absent' => 'red'] as $status => $color)
                            <button type="button" wire:click="$set('records.{{ $learner->id }}', '{{ $status }}')"
                                    class="px-3 py-1 rounded-full text-xs font-medium capitalize
                                        {{ ($records[$learner->id] ?? 'present') === $status ? "bg-{$color}-600 text-white" : "bg-{$color}-50 text-{$color}-700" }}">
                                {{ $status }}
                            </button>
                            @endforeach
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-gray-200 bg-gray-50 flex justify-end">
            <button wire:click="save" class="bg-green-700 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-green-800">
                Save Attendance
            </button>
        </div>
    </div>
    @endif
</div>
