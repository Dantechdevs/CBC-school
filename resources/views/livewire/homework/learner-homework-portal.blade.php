<div>
    <h2 class="text-xl font-bold text-gray-800 mb-6">My Homework</h2>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 text-sm rounded-lg px-4 py-3 mb-5">
        {{ session('success') }}
    </div>
    @endif

    @if(!$learner)
    <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 text-sm rounded-lg px-4 py-3">
        Your account is not linked to a learner record yet. Please contact the school office.
    </div>
    @elseif($homework->isEmpty())
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center text-gray-400">
        No homework has been assigned to your class this term.
    </div>
    @else
    <div class="space-y-3">
        @foreach($homework as $hw)
        @php $submission = $hw->submissions->first(); @endphp
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-800">{{ $hw->title }}</p>
                    <p class="text-xs text-gray-400 mb-1">{{ $hw->learningArea->name ?? '' }} · due {{ $hw->due_at->format('d M Y, H:i') }}</p>
                    @if($hw->description)
                    <p class="text-sm text-gray-600 mt-1">{{ $hw->description }}</p>
                    @endif

                    @if($submission?->status === 'graded')
                    <div class="mt-2 bg-green-50 border border-green-100 rounded-lg px-3 py-2 text-sm">
                        <span class="font-medium text-green-800">Graded</span>
                        @if($submission->score !== null)
                            — {{ $submission->score }}/{{ $submission->max_score }}
                        @endif
                        @if($submission->rubric_level)
                            <span class="ml-1 px-1.5 py-0.5 bg-green-200 text-green-800 rounded text-xs font-bold">{{ $submission->rubric_level->value }}</span>
                        @endif
                        @if($submission->feedback)
                        <p class="text-gray-600 mt-1">"{{ $submission->feedback }}"</p>
                        @endif
                    </div>
                    @endif
                </div>
                <div class="text-right shrink-0">
                    <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium mb-2
                        {{ match($submission->status ?? 'pending') {
                            'graded' => 'bg-green-100 text-green-700',
                            'submitted' => 'bg-blue-100 text-blue-700',
                            'late' => 'bg-orange-100 text-orange-700',
                            default => 'bg-gray-100 text-gray-500',
                        } }}">
                        {{ ucfirst($submission->status ?? 'pending') }}
                    </span><br>
                    @if(($submission?->status ?? 'pending') !== 'graded')
                    <button wire:click="openSubmit({{ $hw->id }})" class="text-xs font-medium text-blue-600 hover:text-blue-800">
                        {{ $submission ? 'Edit Submission' : 'Submit' }}
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Submit modal --}}
    @if($submittingHomeworkId)
    <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50" wire:click.self="closeSubmit">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800">Submit Homework</h3>
                <button wire:click="closeSubmit" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>
            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Your answer / notes</label>
                    <textarea wire:model="submissionText" rows="5" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"></textarea>
                    @error('submissionText') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Attach a file (optional)</label>
                    <input wire:model="uploadedFile" type="file" class="w-full text-sm">
                    <div wire:loading wire:target="uploadedFile" class="text-xs text-gray-400 mt-1">Uploading...</div>
                    @error('uploadedFile') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="mt-5 flex justify-end gap-2">
                <button wire:click="closeSubmit" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Cancel</button>
                <button wire:click="submit" class="bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-800">Submit</button>
            </div>
        </div>
    </div>
    @endif
</div>
