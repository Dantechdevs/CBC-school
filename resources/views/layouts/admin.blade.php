{{-- System Users - Only visible to super-admin --}}
@role('super-admin')
<div class="border-t border-gray-100 px-4 py-2">
    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">System Users</p>
    @foreach(\App\Models\User::with('roles')->latest()->take(6)->get() as $user)
    <div class="flex items-center gap-2 py-1.5">
        <div class="w-7 h-7 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600 shrink-0">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-xs font-medium text-gray-800 truncate">{{ $user->name }}</p>
            <p class="text-xs text-gray-400 capitalize truncate">{{ $user->getRoleNames()->first() ?? 'User' }}</p>
        </div>
        @if($user->id === auth()->id())
        <span class="text-xs text-green-600 font-semibold shrink-0">You</span>
        @else
        <span class="w-2 h-2 rounded-full bg-gray-300 shrink-0"></span>
        @endif
    </div>
    @endforeach
    <a href="#" class="block mt-2 text-xs text-green-700 font-medium hover:underline">Manage all users →</a>
</div>
@endrole

{{-- Principal sees only staff summary --}}
@role('principal|deputy-principal')
<div class="border-t border-gray-100 px-4 py-2">
    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Staff Overview</p>
    @foreach(\App\Models\User::with('roles')->whereHas('roles', fn($q) => $q->whereIn('name', ['teacher','class-teacher','hod','bursar']))->latest()->take(4)->get() as $user)
    <div class="flex items-center gap-2 py-1.5">
        <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center text-xs font-bold text-blue-600 shrink-0">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-xs font-medium text-gray-800 truncate">{{ $user->name }}</p>
            <p class="text-xs text-gray-400 capitalize truncate">{{ $user->getRoleNames()->first() ?? 'Staff' }}</p>
        </div>
    </div>
    @endforeach
</div>
@endrole

{{-- Teacher sees only their own info --}}
@role('teacher|class-teacher|hod')
<div class="border-t border-gray-100 px-4 py-3">
    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">My Info</p>
    <div class="space-y-1.5">
        <div class="flex justify-between text-xs">
            <span class="text-gray-400">Role</span>
            <span class="font-medium text-gray-700 capitalize">{{ auth()->user()->getRoleNames()->first() }}</span>
        </div>
        <div class="flex justify-between text-xs">
            <span class="text-gray-400">Email</span>
            <span class="font-medium text-gray-700 truncate ml-2">{{ auth()->user()->email }}</span>
        </div>
        <div class="flex justify-between text-xs">
            <span class="text-gray-400">Term</span>
            <span class="font-medium text-gray-700">Term {{ config('school.current_term') }}</span>
        </div>
    </div>
</div>
@endrole

{{-- Bursar sees finance summary --}}
@role('bursar')
<div class="border-t border-gray-100 px-4 py-3">
    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Finance Summary</p>
    <div class="space-y-1.5">
        <div class="flex justify-between text-xs">
            <span class="text-gray-400">Term</span>
            <span class="font-medium text-gray-700">Term {{ config('school.current_term') }}</span>
        </div>
        <div class="flex justify-between text-xs">
            <span class="text-gray-400">Year</span>
            <span class="font-medium text-gray-700">{{ config('school.current_academic_year') }}</span>
        </div>
    </div>
</div>
@endrole