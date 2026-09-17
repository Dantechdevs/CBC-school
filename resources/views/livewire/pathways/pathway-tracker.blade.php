<div>
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">Pathway Readiness (Grade 7–9)</h2>
    </div>

    {{-- Cohort distribution --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        @foreach($pathwayNames as $pathway)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">{{ $pathway }}</p>
            <p class="text-2xl font-bold text-gray-900">{{ $distribution[$pathway] ?? 0 }}</p>
            <p class="text-xs text-gray-400">learners leaning this way</p>
        </div>
        @endforeach
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Not Enough Data</p>
            <p class="text-2xl font-bold text-gray-400">{{ $unranked }}</p>
            <p class="text-xs text-gray-400">no assessments yet this term</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4 mb-5 flex flex-wrap gap-4">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search learner..."
               class="flex-1 min-w-48 border border-gray-300 rounded-lg px-3 py-2 text-sm">
        <select wire:model.live="gradeFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <option value="">All Grades</option>
            <option value="Grade 7">Grade 7</option>
            <option value="Grade 8">Grade 8</option>
            <option value="Grade 9">Grade 9</option>
        </select>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Learner</th>
                    @foreach($pathwayNames as $pathway)
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ $pathway }}</th>
                    @endforeach
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Recommended</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($rows as $row)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <p class="text-sm font-medium text-gray-800">{{ $row['learner']->full_name }}</p>
                        <p class="text-xs text-gray-400">{{ $row['learner']->grade_level?->value }}</p>
                    </td>
                    @foreach($pathwayNames as $pathway)
                    <td class="px-4 py-3 text-sm text-gray-700">
                        {{ $row['pathways'][$pathway]['percentage'] !== null ? number_format($row['pathways'][$pathway]['percentage'], 1).'%' : '—' }}
                    </td>
                    @endforeach
                    <td class="px-4 py-3">
                        @if($row['recommended'])
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">{{ $row['recommended'] }}</span>
                        @else
                        <span class="text-xs text-gray-400">No data yet</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ count($pathwayNames) + 2 }}" class="px-4 py-12 text-center text-gray-400">
                        No Grade 7-9 learners found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
            {{ $learners->links() }}
        </div>
    </div>
</div>
