<div>
    <h2 class="text-xl font-bold text-gray-800 mb-6">Homework</h2>

    @if($children->isEmpty())
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center text-gray-400">
        No children are linked to your account yet. Please contact the school office.
    </div>
    @else
        @foreach($children as $child)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-5">
            <h3 class="font-semibold text-gray-800 mb-3">{{ $child->full_name }} <span class="text-xs text-gray-400 font-normal">({{ $child->grade_level?->value }})</span></h3>

            @php $items = $childHomework[$child->id] ?? collect(); @endphp

            @if($items->isEmpty())
            <p class="text-sm text-gray-400">No homework assigned this term.</p>
            @else
            <div class="space-y-2">
                @foreach($items as $hw)
                @php $submission = $hw->submissions->first(); @endphp
                <div class="flex items-center justify-between border border-gray-100 rounded-lg px-3 py-2">
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $hw->title }}</p>
                        <p class="text-xs text-gray-400">{{ $hw->learningArea->name ?? '' }} · due {{ $hw->due_at->format('d M Y, H:i') }}</p>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium
                        {{ match($submission->status ?? 'pending') {
                            'graded' => 'bg-green-100 text-green-700',
                            'submitted', 'late' => 'bg-blue-100 text-blue-700',
                            default => 'bg-yellow-100 text-yellow-700',
                        } }}">
                        {{ ucfirst($submission->status ?? 'pending') }}
                        @if($submission && $submission->status === 'graded' && $submission->score !== null)
                            — {{ $submission->score }}/{{ $submission->max_score }}
                        @endif
                    </span>
                </div>
                @endforeach
            </div>
            @endif
        </div>
        @endforeach
    @endif
</div>
