<div>
    {{-- Header bar --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">Staff & HR</h2>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Total Active Staff</p>
            <p class="text-2xl font-bold text-gray-900">{{ $totalActive }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Teaching Staff</p>
            <p class="text-2xl font-bold text-blue-700">{{ $teaching }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Non-Teaching Staff</p>
            <p class="text-2xl font-bold text-purple-700">{{ $nonTeaching }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm p-4 mb-5 flex flex-wrap gap-4">
        <input wire:model.live.debounce.300ms="search" type="text"
               placeholder="Search by name, staff no., TSC no..."
               class="flex-1 min-w-48 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent">
        <select wire:model.live="departmentFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <option value="">All Departments</option>
            @foreach($departments as $dept)
                <option value="{{ $dept }}">{{ $dept }}</option>
            @endforeach
        </select>
        <select wire:model.live="typeFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <option value="">All Types</option>
            <option value="teaching">Teaching</option>
            <option value="non_teaching">Non-Teaching</option>
        </select>
        <select wire:model.live="statusFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Staff Member</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Staff No.</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">TSC No.</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Designation</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($staff as $member)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="h-9 w-9 rounded-full bg-green-100 flex items-center justify-center text-sm font-bold text-green-700">
                                {{ strtoupper(substr($member->first_name,0,1).substr($member->last_name,0,1)) }}
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900">{{ $member->full_name }}</div>
                                <div class="text-xs text-gray-500">{{ $member->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-700 font-mono">{{ $member->staff_number }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700 font-mono">{{ $member->tsc_number ?? '—' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $member->department ?? '—' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $member->designation ?? '—' }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $member->staff_type === 'teaching' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                            {{ $member->staff_type === 'teaching' ? 'Teaching' : 'Non-Teaching' }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $member->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $member->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <button wire:click="view({{ $member->id }})" class="text-blue-600 hover:text-blue-800 text-xs font-medium">View</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-12 text-center text-gray-400">
                        <svg class="mx-auto h-10 w-10 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <p class="text-sm">No staff members found. Adjust your filters.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
            {{ $staff->links() }}
        </div>
    </div>

    {{-- View Detail Modal --}}
    @if($viewingStaff)
    <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50" wire:click.self="closeView">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800">{{ $viewingStaff->full_name }}</h3>
                <button wire:click="closeView" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div><p class="text-gray-400 text-xs">Staff No.</p><p class="font-medium text-gray-800">{{ $viewingStaff->staff_number }}</p></div>
                <div><p class="text-gray-400 text-xs">TSC No.</p><p class="font-medium text-gray-800">{{ $viewingStaff->tsc_number ?? '—' }}</p></div>
                <div><p class="text-gray-400 text-xs">Email</p><p class="font-medium text-gray-800">{{ $viewingStaff->email }}</p></div>
                <div><p class="text-gray-400 text-xs">Phone</p><p class="font-medium text-gray-800">{{ $viewingStaff->phone_number ?? '—' }}</p></div>
                <div><p class="text-gray-400 text-xs">Department</p><p class="font-medium text-gray-800">{{ $viewingStaff->department ?? '—' }}</p></div>
                <div><p class="text-gray-400 text-xs">Designation</p><p class="font-medium text-gray-800">{{ $viewingStaff->designation ?? '—' }}</p></div>
                <div><p class="text-gray-400 text-xs">Employment Type</p><p class="font-medium text-gray-800 capitalize">{{ str_replace('_',' ', $viewingStaff->employment_type ?? '—') }}</p></div>
                <div><p class="text-gray-400 text-xs">Date Joined</p><p class="font-medium text-gray-800">{{ $viewingStaff->date_joined?->format('d M Y') ?? '—' }}</p></div>
            </div>
        </div>
    </div>
    @endif
</div>
