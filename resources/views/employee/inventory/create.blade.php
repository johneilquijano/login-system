<x-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="flex flex-row h-screen">
            <x-employee-sidebar />

            <!-- Main Content Area -->
            <div class="flex-1 overflow-auto">
                <!-- Header -->
                <header class="sticky top-0 z-40 bg-white shadow-sm border-b" style="border-bottom-color: #ccc;">
                    <div class="flex items-center justify-between px-8 py-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">New Inventory Request</h2>
                            <p class="text-sm text-gray-600 mt-1">Submit a request for inventory items</p>
                        </div>
                    </div>
                </header>

                <!-- Main Content -->
                <div class="p-8">
                    <!-- Form Card -->
                    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-8 max-w-2xl">
                        <form method="POST" action="{{ route('inventory.store') }}">
                            @csrf

                            <!-- Item Name -->
                            <div class="mb-6">
                                <label for="item_name" class="block text-sm font-semibold text-gray-900 mb-2">Item Name</label>
                                <input 
                                    type="text" 
                                    id="item_name" 
                                    name="item_name" 
                                    value="{{ old('item_name') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('item_name') border-red-500 @enderror"
                                    placeholder="e.g., Office Chair, Laptop, etc."
                                    required
                                >
                                @error('item_name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Quantity -->
                            <div class="mb-6">
                                <label for="quantity" class="block text-sm font-semibold text-gray-900 mb-2">Quantity</label>
                                <input 
                                    type="number" 
                                    id="quantity" 
                                    name="quantity" 
                                    value="{{ old('quantity', 1) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('quantity') border-red-500 @enderror"
                                    min="1"
                                    required
                                >
                                @error('quantity')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div class="mb-6">
                                <label for="category" class="block text-sm font-semibold text-gray-900 mb-2">Category</label>
                                <select 
                                    id="category" 
                                    name="category"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white @error('category') border-red-500 @enderror"
                                    required
                                >
                                    <option value="">Select a category</option>
                                    <option value="Office Supplies" {{ old('category') === 'Office Supplies' ? 'selected' : '' }}>Office Supplies</option>
                                    <option value="Equipment" {{ old('category') === 'Equipment' ? 'selected' : '' }}>Equipment</option>
                                    <option value="Technology" {{ old('category') === 'Technology' ? 'selected' : '' }}>Technology</option>
                                    <option value="Tools" {{ old('category') === 'Tools' ? 'selected' : '' }}>Tools</option>
                                    <option value="Other" {{ old('category') === 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('category')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="mb-6">
                                <label for="description" class="block text-sm font-semibold text-gray-900 mb-2">Description (Optional)</label>
                                <textarea 
                                    id="description" 
                                    name="description" 
                                    rows="4"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('description') border-red-500 @enderror"
                                    placeholder="Provide any additional details about your request..."
                                >{{ old('description') }}</textarea>
                                @error('description')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Form Actions -->
                            <div class="flex items-center gap-3">
                                <button 
                                    type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition"
                                >
                                    Submit Request
                                </button>
                                <a 
                                    href="{{ route('inventory.index') }}" 
                                    class="bg-gray-200 hover:bg-gray-300 text-gray-900 font-semibold py-3 px-6 rounded-lg transition"
                                >
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
