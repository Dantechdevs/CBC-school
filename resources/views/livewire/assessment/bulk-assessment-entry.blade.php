<div>
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">Bulk Assessment Entry</h2>
        <span class="text-sm text-gray-500 bg-green-50 px-3 py-1 rounded-full border border-green-200">CBC Rubric: EE · ME · AE · BE</span>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm p-5 mb-5 grid grid-cols-2 md:grid-cols-4 gap-4">
        <div>
            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">Class</label>
            <select wire:model.live="classId" wire:change="loadLearners" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="">Select class...</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }} — {{ $class->grade_level }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">Learning Area</label>
            <select wire:model.live="learningAreaId" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="">Select area...</option>
                @foreach($learningAreas as $area)
                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">Term</label>
            <select wire:model="term" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                @foreach($terms as $t)
                    <option value="{{ $t->value }}">{{ $t->label() }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">Type</label>
            <select wire:model="assessmentType" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="formative">Formative (40%)</option>
                <option value="summative">Summative (60%)</option>
            </select>
        </div>
    </div>

    {{-- Assessment table --}}
    @if(count($assessmentData))
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Learner</th>
                    @foreach($rubricLevels as $level)
                    <th class="px-3 py-3 text-center text-xs font-bold uppercase
                        {{ $level->value === 'EE' ? 'text-green-700' :
                          ($level->value === 'ME' ? 'text-blue-700' :
                          ($level->value === 'AE' ? 'text-yellow-700' : 'text-red-700')) }}">
                        {{ $level->value }}
                    </th>
                    @endforeach
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Remarks</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @foreach($assessmentData as $learnerId => $data)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $loop->iteration }}</td>
                    <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ $data['name'] }}</td>
                    @foreach($rubricLevels as $level)
                    <td class="px-3 py-3 text-center">
                        <input type="radio"
                            wire:model="assessmentData.{{ $learnerId }}.rubric_level"
                            value="{{ $level->value }}"
                            class="w-4 h-4 {{ $level->value === 'EE' ? 'accent-green-600' : ($level->value === 'ME' ? 'accent-blue-600' : ($level->value === 'AE' ? 'accent-yellow-500' : 'accent-red-600')) }}">
                    </td>
                    @endforeach
                    <td class="px-4 py-3">
                        <input type="text"
                            wire:model="assessmentData.{{ $learnerId }}.remarks"
                            placeholder="Optional remarks..."
                            class="w-full border border-gray-200 rounded px-2 py-1 text-xs focus:ring-1 focus:ring-green-500">
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-4 py-4 border-t border-gray-200 bg-gray-50 flex justify-end">
            <button wire:click="saveAssessments" wire:loading.attr="disabled"
                class="bg-green-700 text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-green-800 disabled:opacity-50">
                <span wire:loading.remove>Save All Assessments</span>
                <span wire:loading>Saving...</span>
            </button>
        </div>
    </div>
    @elseif($classId)
    <div class="bg-white rounded-xl shadow-sm p-12 text-center text-gray-400">
        <p class="text-sm">No learners found in this class.</p>
    </div>
    @else
    <div class="bg-white rounded-xl shadow-sm p-12 text-center text-gray-400">
        <p class="text-sm">Select a class and learning area to begin assessment entry.</p>
    </div>
    @endif
</div>
