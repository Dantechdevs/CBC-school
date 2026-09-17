<div>
    {{-- Header bar --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Learners Register</h2>
            <p class="text-xs text-gray-400">{{ number_format($totalCount) }} registered</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.students.import') }}" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50">
                Import Learners
            </a>
            <button wire:click="create" class="bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-800">
                + Enrol Learner
            </button>
        </div>
    </div>

    @if($flashMessage)
    <div class="bg-green-50 border border-green-200 text-green-800 text-sm rounded-lg px-4 py-3 mb-5">
        {{ $flashMessage }}
    </div>
    @endif

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm p-4 mb-5 flex flex-wrap gap-4">
        <input wire:model.live.debounce.300ms="search" type="text"
               placeholder="Search by name, admission no., KEMIS UPI..."
               class="flex-1 min-w-48 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent">
        <select wire:model.live="gradeFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <option value="">All Grades</option>
            @foreach(config('school.grade_levels') as $level => $grades)
                <optgroup label="{{ str_replace('_',' ', ucwords($level)) }}">
                    @foreach($grades as $grade)
                        <option value="{{ $grade }}">{{ $grade }}</option>
                    @endforeach
                </optgroup>
            @endforeach
        </select>
        <select wire:model.live="statusFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <option value="">All Status</option>
            <option value="1">Active</option>
            <option value="0">Inactive</option>
        </select>
        <select wire:model.live="boardingFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <option value="">Day & Boarding</option>
            <option value="day">Day</option>
            <option value="boarding">Boarding</option>
        </select>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Learner</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Adm. No.</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">KEMIS UPI</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($learners as $learner)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="h-9 w-9 rounded-full {{ $learner->gender === 'male' ? 'bg-blue-100' : 'bg-pink-100' }} flex items-center justify-center text-sm font-bold {{ $learner->gender === 'male' ? 'text-blue-700' : 'text-pink-700' }}">
                                {{ strtoupper(substr($learner->first_name,0,1).substr($learner->last_name,0,1)) }}
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900">{{ $learner->full_name }}</div>
                                <div class="text-xs text-gray-500">{{ ucfirst($learner->gender) }} · Age {{ $learner->age }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-700 font-mono">{{ $learner->admission_number }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700 font-mono">{{ $learner->kemis_upi ?? '<span class="text-gray-400 italic">Not set</span>' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $learner->grade_level->value }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $learner->schoolClass->name ?? '—' }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $learner->boarding_status === 'boarding' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ ucfirst($learner->boarding_status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $learner->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $learner->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 flex items-center gap-2">
                        <button wire:click="view({{ $learner->id }})" class="text-blue-600 hover:text-blue-800 text-xs font-medium">View</button>
                        <button wire:click="edit({{ $learner->id }})" class="text-green-600 hover:text-green-800 text-xs font-medium">Edit</button>
                        <button wire:click="generateReport({{ $learner->id }})" class="text-purple-600 hover:text-purple-800 text-xs font-medium">Report</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-12 text-center text-gray-400">
                        <svg class="mx-auto h-10 w-10 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
                        <p class="text-sm">No learners found. Adjust your filters or enrol a new learner.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
            {{ $learners->links() }}
        </div>
    </div>

    {{-- Create/Edit Modal --}}
    @if($showFormModal)
    <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50" wire:click.self="closeModals">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800">{{ $isEditing ? 'Edit Learner' : 'Enrol Learner' }}</h3>
                <button wire:click="closeModals" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div class="col-span-2">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Admission Number</label>
                    <input wire:model="admissionNumber" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    @error('admissionNumber') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">First Name</label>
                    <input wire:model="firstName" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    @error('firstName') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Last Name</label>
                    <input wire:model="lastName" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    @error('lastName') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Gender</label>
                    <select wire:model="gender" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Date of Birth</label>
                    <input wire:model="dateOfBirth" type="date" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    @error('dateOfBirth') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Grade Level</label>
                    <select wire:model="gradeLevel" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="">Select...</option>
                        @foreach(config('school.grade_levels') as $level => $grades)
                            <optgroup label="{{ str_replace('_',' ', ucwords($level)) }}">
                                @foreach($grades as $grade)
                                    <option value="{{ $grade }}">{{ $grade }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    @error('gradeLevel') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Class</label>
                    <select wire:model="classId" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="">Unassigned</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Boarding Status</label>
                    <select wire:model="boardingStatus" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="day">Day</option>
                        <option value="boarding">Boarding</option>
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-medium text-gray-600 mb-1">KEMIS UPI (optional)</label>
                    <input wire:model="kemisUpi" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
            </div>
            <div class="mt-5 flex justify-end gap-2">
                <button wire:click="closeModals" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Cancel</button>
                <button wire:click="save" class="bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-800">
                    {{ $isEditing ? 'Save Changes' : 'Enrol Learner' }}
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- View Detail Modal --}}
    @if($showViewModal && $viewingLearner)
    <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50" wire:click.self="closeModals">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800">{{ $viewingLearner->full_name }}</h3>
                <button wire:click="closeModals" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>
            <div class="grid grid-cols-2 gap-3 text-sm mb-4">
                <div><p class="text-gray-400 text-xs">Admission No.</p><p class="font-medium text-gray-800">{{ $viewingLearner->admission_number }}</p></div>
                <div><p class="text-gray-400 text-xs">KEMIS UPI</p><p class="font-medium text-gray-800">{{ $viewingLearner->kemis_upi ?? '—' }}</p></div>
                <div><p class="text-gray-400 text-xs">Grade</p><p class="font-medium text-gray-800">{{ $viewingLearner->grade_level?->value }}</p></div>
                <div><p class="text-gray-400 text-xs">Class</p><p class="font-medium text-gray-800">{{ $viewingLearner->schoolClass->name ?? '—' }}</p></div>
                <div><p class="text-gray-400 text-xs">Gender</p><p class="font-medium text-gray-800">{{ ucfirst($viewingLearner->gender) }}</p></div>
                <div><p class="text-gray-400 text-xs">Age</p><p class="font-medium text-gray-800">{{ $viewingLearner->age }}</p></div>
                <div><p class="text-gray-400 text-xs">Boarding Status</p><p class="font-medium text-gray-800">{{ ucfirst($viewingLearner->boarding_status) }}</p></div>
                <div><p class="text-gray-400 text-xs">Status</p><p class="font-medium text-gray-800">{{ $viewingLearner->is_active ? 'Active' : 'Inactive' }}</p></div>
            </div>
            <div class="border-t border-gray-100 pt-3">
                <p class="text-xs text-gray-400 mb-2">Guardians</p>
                @forelse($viewingLearner->guardians as $guardian)
                <div class="flex items-center justify-between text-sm py-1">
                    <span class="text-gray-800">{{ $guardian->full_name }} <span class="text-xs text-gray-400">({{ ucfirst($guardian->relationship) }})</span></span>
                    <span class="text-gray-500">{{ $guardian->phone_number }}</span>
                </div>
                @empty
                <p class="text-sm text-gray-400">No guardians linked yet.</p>
                @endforelse
            </div>
            <div class="mt-4 flex justify-end">
                <button wire:click="generateReport({{ $viewingLearner->id }})" class="bg-purple-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-purple-700">
                    Generate Report Card
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
