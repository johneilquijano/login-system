<x-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="flex h-screen">
            <x-admin-sidebar />

            <!-- Main Content Area -->
            <div class="flex-1 overflow-auto">
                <!-- Header -->
                <x-employee-header 
                    title="Edit Tool" 
                    subtitle="Update tool information" 
                />

                <!-- Main Content -->
                <div class="p-4 md:p-6">
                    <div class="max-w-2xl mx-auto">
                        <form action="{{ route('admin.tools.update', $tool) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            @csrf
                            @method('PUT')

                            <!-- Tool Name -->
                            <div class="mb-6">
                                <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">Tool Name *</label>
                                <input 
                                    type="text" 
                                    name="name" 
                                    id="name"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                                    value="{{ old('name', $tool->name) }}"
                                    required>
                                @error('name')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div class="mb-6">
                                <label for="category" class="block text-sm font-semibold text-gray-900 mb-2">Category *</label>
                                <input 
                                    type="text" 
                                    name="category" 
                                    id="category"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category') border-red-500 @enderror"
                                    placeholder="e.g., Power Tools, Hand Tools"
                                    value="{{ old('category', $tool->category) }}"
                                    required>
                                @error('category')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="mb-6">
                                <label for="description" class="block text-sm font-semibold text-gray-900 mb-2">Description</label>
                                <textarea 
                                    name="description" 
                                    id="description"
                                    rows="4"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                                    placeholder="Tool description...">{{ old('description', $tool->description) }}</textarea>
                                @error('description')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Quantity -->
                            <div class="mb-6">
                                <label for="quantity" class="block text-sm font-semibold text-gray-900 mb-2">Quantity *</label>
                                <input 
                                    type="number" 
                                    name="quantity" 
                                    id="quantity"
                                    min="1"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('quantity') border-red-500 @enderror"
                                    value="{{ old('quantity', $tool->quantity) }}"
                                    required>
                                @error('quantity')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Image -->
                            <div class="mb-6">
                                <label for="image_path" class="block text-sm font-semibold text-gray-900 mb-2">Tool Image</label>
                                @if($tool->image_path)
                                    <div class="mb-4">
                                        <img src="{{ asset($tool->image_path) }}" alt="{{ $tool->name }}" class="max-w-xs h-auto rounded-lg border border-gray-200">
                                        <p class="text-sm text-gray-600 mt-2">Current image</p>
                                    </div>
                                @endif
                                <input 
                                    type="file" 
                                    name="image_path" 
                                    id="image_path"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('image_path') border-red-500 @enderror"
                                    accept="image/*">
                                @error('image_path')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Maintenance Status -->
                            <div class="mb-6">
                                <label class="flex items-center">
                                    <input 
                                        type="checkbox" 
                                        name="is_maintenance" 
                                        value="1"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                        {{ old('is_maintenance', $tool->is_maintenance) ? 'checked' : '' }}>
                                    <span class="ml-3 text-sm font-medium text-gray-900">Mark as Maintenance Tool</span>
                                </label>
                            </div>

                            <!-- Form Actions -->
                            <div class="flex gap-4 pt-6 border-t border-gray-200">
                                <a href="{{ route('admin.tools.index') }}" class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg font-semibold text-gray-900 hover:bg-gray-50 transition text-center">
                                    Cancel
                                </a>
                                <button type="submit" class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                                    Update Tool
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
