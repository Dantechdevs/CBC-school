<div>
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">Analytics & Reports</h2>
        <span class="text-sm text-gray-500 bg-green-50 px-3 py-1 rounded-full border border-green-200">
            Term {{ $term }}, {{ $academicYear }}
        </span>
    </div>

    {{-- Top stat row --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Attendance Rate</p>
            <p class="text-2xl font-bold text-blue-700">{{ $attendanceRate }}%</p>
            <p class="text-xs text-gray-400 mt-0.5">All recorded sessions</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Fee Collection Rate</p>
            <p class="text-2xl font-bold text-green-700">{{ $collectionRate }}%</p>
            <p class="text-xs text-gray-400 mt-0.5">KES {{ number_format($feesCollected) }} of {{ number_format($feesCollected + $feeArrears) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Active Learners</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalLearners) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Active Classes</p>
            <p class="text-2xl font-bold text-gray-900">{{ $totalClasses }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">

        {{-- Rubric distribution --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-800 mb-4">CBC Rubric Distribution (School-Wide)</h3>
            <div class="grid grid-cols-2 gap-3">
                @foreach([
                    'EE' => ['Exceeds Expectation', 'green'],
                    'ME' => ['Meets Expectation', 'blue'],
                    'AE' => ['Approaches Expectation', 'yellow'],
                    'BE' => ['Below Expectation', 'red'],
                ] as $code => [$label, $color])
                <div class="p-3 bg-{{ $color }}-50 rounded-lg border border-{{ $color }}-100">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-xs font-bold text-{{ $color }}-700 bg-{{ $color }}-200 px-2 py-0.5 rounded">{{ $code }}</span>
                        <span class="text-lg font-bold text-{{ $color }}-800">{{ $rubricCounts[$code] ?? 0 }}</span>
                    </div>
                    <p class="text-xs text-{{ $color }}-600 leading-tight">{{ $label }}</p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Class averages --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-800 mb-4">Class Performance Ranking</h3>
            @if($classAverages->isEmpty())
            <p class="text-sm text-gray-400 text-center py-6">No assessments recorded for this term yet.</p>
            @else
            <div class="space-y-2 max-h-72 overflow-y-auto">
                @foreach($classAverages as $class)
                <div class="flex items-center justify-between py-1.5 border-b border-gray-50 last:border-0">
                    <div>
                        <p class="text-sm text-gray-800 font-medium">{{ $class->name }}</p>
                        <p class="text-xs text-gray-400">{{ $class->total }} assessment{{ $class->total == 1 ? '' : 's' }}</p>
                    </div>
                    <span class="text-sm font-bold {{ $class->avg_score >= 3 ? 'text-green-700' : ($class->avg_score >= 2 ? 'text-yellow-700' : 'text-red-700') }}">
                        {{ number_format($class->avg_score, 2) }} / 4.0
                    </span>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    {{-- At-risk learners --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800">At-Risk Learners</h3>
            <span class="text-xs text-gray-400">Average rubric below "Approaches Expectation" this term</span>
        </div>
        @if($atRiskLearners->isEmpty())
        <div class="text-center py-8">
            <svg class="w-10 h-10 text-green-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-sm text-gray-400">No learners currently flagged as at-risk. 🎉</p>
        </div>
        @else
        <table class="min-w-full divide-y divide-gray-100">
            <thead>
                <tr>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Learner</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Adm. No.</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Avg. Score</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Assessments</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($atRiskLearners as $learner)
                <tr>
                    <td class="px-3 py-2 text-sm text-gray-800 font-medium">{{ $learner->first_name }} {{ $learner->last_name }}</td>
                    <td class="px-3 py-2 text-sm text-gray-600 font-mono">{{ $learner->admission_number }}</td>
                    <td class="px-3 py-2 text-sm font-bold text-red-700">{{ number_format($learner->avg_score, 2) }} / 4.0</td>
                    <td class="px-3 py-2 text-sm text-gray-600">{{ $learner->total }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
