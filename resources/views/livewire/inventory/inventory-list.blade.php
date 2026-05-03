<div>
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">Inventory Management</h2>
        <div class="flex gap-2">
            <button class="border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">📥 Receive Stock</button>
            <button class="bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-800">+ Add Item</button>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 rounded-lg px-4 py-3 text-sm text-green-800">{{ session('success') }}</div>
    @endif

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm p-4 mb-5 flex flex-wrap gap-4">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search items..."
               class="flex-1 min-w-48 border border-gray-300 rounded-lg px-3 py-2 text-sm">
        <select wire:model.live="typeFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <option value="">All Items</option>
            <option value="textbook">Textbooks Only</option>
            <option value="low_stock">⚠️ Low Stock</option>
        </select>
        <label class="flex items-center gap-2 text-sm text-gray-600">
            <input wire:model.live="lowStock" type="checkbox" class="rounded"> Show low stock only
        </label>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">In Stock</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Issued</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Min Level</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($items as $item)
                @php $isLow = $item->quantity_in_stock <= $item->minimum_stock_level; @endphp
                <tr class="hover:bg-gray-50 {{ $isLow ? 'bg-red-50' : '' }}">
                    <td class="px-4 py-3">
                        <div class="text-sm font-medium text-gray-900">{{ $item->name }}</div>
                        @if($item->is_textbook) <span class="text-xs text-blue-600">📚 Textbook</span> @endif
                        @if($item->grade_level) <span class="text-xs text-gray-400">· {{ $item->grade_level }}</span> @endif
                    </td>
                    <td class="px-4 py-3 text-sm font-mono text-gray-600">{{ $item->code }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ $item->category->name ?? '—' }}</td>
                    <td class="px-4 py-3">
                        <span class="text-sm font-bold {{ $isLow ? 'text-red-700' : 'text-gray-900' }}">
                            {{ $item->quantity_in_stock }} {{ $item->unit }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ $item->quantity_issued }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ $item->minimum_stock_level }}</td>
                    <td class="px-4 py-3">
                        @if($item->quantity_in_stock == 0)
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">Out of Stock</span>
                        @elseif($isLow)
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">⚠️ Low Stock</span>
                        @else
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">In Stock</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 flex gap-2">
                        <button wire:click="openIssueModal({{ $item->id }})"
                                class="text-xs text-blue-600 hover:text-blue-800 font-medium"
                                @if($item->quantity_in_stock == 0) disabled @endif>Issue</button>
                        <button class="text-xs text-green-600 hover:text-green-800 font-medium">Receive</button>
                        <button class="text-xs text-gray-500 hover:text-gray-700 font-medium">History</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-4 py-10 text-center text-gray-400 text-sm">No inventory items found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t bg-gray-50">{{ $items->links() }}</div>
    </div>

    {{-- Issue Modal --}}
    @if($showIssueModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
        <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Issue Item</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Quantity</label>
                    <input wire:model="issueQty" type="number" min="1"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    @error('issueQty')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Issue To</label>
                    <select wire:model="issueType" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="learner">Learner</option>
                        <option value="staff">Staff</option>
                        <option value="class">Class</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Remarks</label>
                    <input wire:model="issueRemarks" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button wire:click="closeIssueModal" class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                <button wire:click="issueItem" class="px-4 py-2 text-sm bg-green-700 text-white rounded-lg hover:bg-green-800">Confirm Issue</button>
            </div>
        </div>
    </div>
    @endif
</div>
