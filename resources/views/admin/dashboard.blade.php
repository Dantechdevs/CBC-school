<x-app-layout>
    <x-slot name="header">Admin Dashboard</x-slot>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-green-500">
            <div class="text-sm font-medium text-gray-500">Total Learners</div>
            <div class="text-3xl font-bold text-gray-900 mt-1">—</div>
            <div class="text-xs text-green-600 mt-1">Active enrollments</div>
        </div>
        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-blue-500">
            <div class="text-sm font-medium text-gray-500">Staff Members</div>
            <div class="text-3xl font-bold text-gray-900 mt-1">—</div>
            <div class="text-xs text-blue-600 mt-1">Teaching &amp; non-teaching</div>
        </div>
        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-yellow-500">
            <div class="text-sm font-medium text-gray-500">Fees Collected</div>
            <div class="text-3xl font-bold text-gray-900 mt-1">KES —</div>
            <div class="text-xs text-yellow-600 mt-1">This term</div>
        </div>
        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-red-500">
            <div class="text-sm font-medium text-gray-500">Fee Arrears</div>
            <div class="text-3xl font-bold text-gray-900 mt-1">KES —</div>
            <div class="text-xs text-red-600 mt-1">Outstanding balance</div>
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="font-semibold text-gray-700 mb-4">CBC Assessment Overview</h3>
            <div class="flex gap-4">
                @foreach(['EE' => 'green', 'ME' => 'blue', 'AE' => 'yellow', 'BE' => 'red'] as $level => $color)
                <div class="flex-1 text-center p-3 bg-{{ $color }}-50 rounded-lg">
                    <div class="text-2xl font-bold text-{{ $color }}-700">—</div>
                    <div class="text-xs text-{{ $color }}-600 font-medium">{{ $level }}</div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="font-semibold text-gray-700 mb-4">Recent Notifications</h3>
            <p class="text-sm text-gray-500">No recent notifications.</p>
        </div>
    </div>
</x-app-layout>
