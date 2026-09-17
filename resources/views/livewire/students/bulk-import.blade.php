<div>
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">Import Learners</h2>
        <a href="{{ route('admin.students.import.template') }}" class="text-sm text-blue-700 hover:underline">Download CSV Template</a>
    </div>

    <div class="bg-blue-50 border border-blue-200 text-blue-800 text-sm rounded-lg px-4 py-3 mb-6">
        Upload a CSV or Excel file with columns: <code class="bg-blue-100 px-1 rounded">admission_number</code>,
        <code class="bg-blue-100 px-1 rounded">first_name</code>, <code class="bg-blue-100 px-1 rounded">last_name</code>,
        <code class="bg-blue-100 px-1 rounded">gender</code>, <code class="bg-blue-100 px-1 rounded">date_of_birth</code>,
        <code class="bg-blue-100 px-1 rounded">grade_level</code>, and optionally
        <code class="bg-blue-100 px-1 rounded">class_name</code>, <code class="bg-blue-100 px-1 rounded">boarding_status</code>,
        <code class="bg-blue-100 px-1 rounded">kemis_upi</code>. Download the template above to get the exact format.
    </div>

    @if(!$processed)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">Select file</label>
        <input wire:model="file" type="file" accept=".csv,.xlsx,.xls" class="block w-full text-sm border border-gray-300 rounded-lg px-3 py-2">
        <div wire:loading wire:target="file" class="text-xs text-gray-400 mt-1">Uploading...</div>
        @error('file') <span class="text-xs text-red-600">{{ $message }}</span> @enderror

        <button wire:click="import" wire:loading.attr="disabled" wire:target="import"
                class="mt-4 bg-green-700 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-green-800 disabled:opacity-50">
            <span wire:loading.remove wire:target="import">Import Learners</span>
            <span wire:loading wire:target="import">Importing...</span>
        </button>
    </div>
    @else
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="font-semibold text-gray-800">{{ $importedCount }} learner(s) imported successfully</p>
                @if(count($importErrors))
                <p class="text-sm text-red-600">{{ count($importErrors) }} row(s) had issues and were skipped</p>
                @endif
            </div>
        </div>

        @if(count($importErrors))
        <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4 max-h-48 overflow-y-auto">
            @foreach($importErrors as $error)
            <p class="text-xs text-red-700 py-0.5">{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <div class="flex gap-3">
            <a href="{{ route('admin.students.index') }}" class="bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-800">View Learners</a>
            <button wire:click="startOver" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Import More</button>
        </div>
    </div>
    @endif
</div>
