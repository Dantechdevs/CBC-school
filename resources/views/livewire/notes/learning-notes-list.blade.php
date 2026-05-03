<div>
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">Learning Notes & Resources</h2>
        <button wire:click="$set('showModal', true)"
                class="bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-800">
            + Upload Resource
        </button>
    </div>

    @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 rounded-lg px-4 py-3 text-sm text-green-800">{{ session('success') }}</div>
    @endif

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm p-4 mb-5 flex flex-wrap gap-3">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search resources..."
               class="flex-1 min-w-48 border border-gray-300 rounded-lg px-3 py-2 text-sm">
        <select wire:model.live="gradeFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <option value="">All Grades</option>
            @foreach(config('school.grade_levels') as $level => $grades)
                <optgroup label="{{ ucwords(str_replace('_',' ',$level)) }}">
                    @foreach($grades as $grade)<option value="{{ $grade }}">{{ $grade }}</option>@endforeach
                </optgroup>
            @endforeach
        </select>
        <select wire:model.live="areaFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <option value="">All Learning Areas</option>
            @foreach($learningAreas as $area)
                <option value="{{ $area->id }}">{{ $area->name }}</option>
            @endforeach
        </select>
        <select wire:model.live="termFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <option value="">All Terms</option>
            @foreach(config('school.terms') as $n => $label)
                <option value="{{ $n }}">{{ $label }}</option>
            @endforeach
        </select>
    </div>

    {{-- Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($notes as $note)
        @php
            $icons = ['pdf'=>'📄','video'=>'🎬','image'=>'🖼️','document'=>'📝','link'=>'🔗','other'=>'📎'];
            $icon = $icons[$note->resource_type] ?? '📎';
        @endphp
        <div class="bg-white rounded-xl shadow-sm p-5 flex flex-col gap-3 border border-gray-100 hover:border-green-200 transition-colors">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-2xl">{{ $icon }}</span>
                    <div>
                        <div class="text-sm font-semibold text-gray-900 leading-tight">{{ $note->title }}</div>
                        <div class="text-xs text-gray-500 mt-0.5">{{ $note->grade_level }} · {{ $note->learningArea->name }}</div>
                    </div>
                </div>
                <span class="text-xs px-2 py-1 rounded-full {{ $note->is_published ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                    {{ $note->is_published ? 'Published' : 'Draft' }}
                </span>
            </div>
            @if($note->description)
            <p class="text-xs text-gray-500 line-clamp-2">{{ $note->description }}</p>
            @endif
            <div class="flex items-center justify-between mt-auto pt-2 border-t border-gray-100">
                <div class="text-xs text-gray-400">
                    Term {{ $note->term }} · {{ $note->download_count }} downloads
                </div>
                <div class="flex gap-3">
                    @if($note->file_path)
                    <a href="{{ Storage::url($note->file_path) }}" target="_blank"
                       class="text-xs text-blue-600 hover:text-blue-800 font-medium">Download</a>
                    @elseif($note->external_url)
                    <a href="{{ $note->external_url }}" target="_blank"
                       class="text-xs text-blue-600 hover:text-blue-800 font-medium">Open Link</a>
                    @endif
                    <button wire:click="togglePublish({{ $note->id }})"
                            class="text-xs text-yellow-600 hover:text-yellow-800 font-medium">
                        {{ $note->is_published ? 'Unpublish' : 'Publish' }}
                    </button>
                    <button wire:click="delete({{ $note->id }})"
                            wire:confirm="Delete this resource?"
                            class="text-xs text-red-500 hover:text-red-700 font-medium">Delete</button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 py-16 text-center text-gray-400">
            <div class="text-5xl mb-3">📚</div>
            <p class="text-sm">No resources found. Upload the first learning note!</p>
        </div>
        @endforelse
    </div>
    <div class="mt-4">{{ $notes->links() }}</div>

    {{-- Upload Modal --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-5 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Upload Learning Resource</h3>
                <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600">✕</button>
            </div>
            <div class="p-5 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Grade <span class="text-red-500">*</span></label>
                        <select wire:model="grade" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <option value="">Select grade...</option>
                            @foreach(config('school.grade_levels') as $level => $grades)
                                <optgroup label="{{ ucwords(str_replace('_',' ',$level)) }}">
                                    @foreach($grades as $g)<option value="{{ $g }}">{{ $g }}</option>@endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Term <span class="text-red-500">*</span></label>
                        <select wire:model="term" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <option value="">Select term...</option>
                            @foreach(config('school.terms') as $n => $label)
                                <option value="{{ $n }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Learning Area <span class="text-red-500">*</span></label>
                    <select wire:model="learningAreaId" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="">Select learning area...</option>
                        @foreach($learningAreas as $area)
                            <option value="{{ $area->id }}">{{ $area->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Title <span class="text-red-500">*</span></label>
                    <input wire:model="title" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Description</label>
                    <textarea wire:model="description" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Resource Type</label>
                        <select wire:model="resourceType" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <option value="pdf">📄 PDF</option>
                            <option value="video">🎬 Video Link</option>
                            <option value="image">🖼️ Image</option>
                            <option value="document">📝 Document</option>
                            <option value="link">🔗 External Link</option>
                        </select>
                    </div>
                </div>
                @if($resourceType === 'link' || $resourceType === 'video')
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">External URL</label>
                    <input wire:model="externalUrl" type="url" placeholder="https://..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
                @else
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Upload File (max 50MB)</label>
                    <input wire:model="uploadedFile" type="file" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <div wire:loading wire:target="uploadedFile" class="text-xs text-gray-500 mt-1">Uploading...</div>
                </div>
                @endif
            </div>
            <div class="flex justify-end gap-3 p-5 border-t">
                <button wire:click="$set('showModal', false)" class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                <button wire:click="upload" class="px-4 py-2 text-sm bg-green-700 text-white rounded-lg hover:bg-green-800">Upload Resource</button>
            </div>
        </div>
    </div>
    @endif
</div>
