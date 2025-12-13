<table class="w-full">
    <thead class="bg-gray-100 border-b" style="border-bottom-color: #ccc;">
        <tr>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Name</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Email</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Slug</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Created</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($organizations as $org)
        <tr class="border-b hover:bg-gray-50 transition" style="border-bottom-color: #ccc;">
            <td class="px-6 py-4 text-sm text-gray-900">{{ $org->name }}</td>
            <td class="px-6 py-4 text-sm text-gray-600">{{ $org->email }}</td>
            <td class="px-6 py-4 text-sm text-gray-600">{{ $org->slug }}</td>
            <td class="px-6 py-4 text-sm">
                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $org->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ ucfirst($org->status) }}
                </span>
            </td>
            <td class="px-6 py-4 text-sm text-gray-600">{{ $org->created_at->format('M d, Y') }}</td>
            <td class="px-6 py-4 text-sm">
                <div class="relative inline-block text-left">
                    <button type="button" data-menu-button="{{ $org->id }}" onclick="toggleMenu({{ $org->id }})" aria-haspopup="true" aria-expanded="false" class="p-2 rounded-full hover:bg-gray-100" style="z-index:1;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </button>

                    <div id="menu-{{ $org->id }}" class="hidden origin-top-right absolute right-0 mt-2 rounded-md shadow-xl bg-white ring-1 ring-black ring-opacity-10" style="background-color:#ffffff; min-width: max-content;">
                        <div class="py-1">
                            <a href="{{ route('super-admin.organizations.show', $org) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">View</a>

                            <a href="{{ route('super-admin.organizations.edit', $org) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">Edit</a>

                            <button type="button" onclick="deleteOrganization({{ $org->id }})" class="w-full text-left block px-4 py-2 text-sm text-red-700 hover:bg-red-50 whitespace-nowrap">Delete</button>

                            @if($org->status === 'active')
                            <button type="button" onclick="disableOrganization({{ $org->id }})" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">Disable</button>
                            @else
                            <button type="button" onclick="enableOrganization({{ $org->id }})" class="w-full text-left block px-4 py-2 text-sm text-green-700 hover:bg-green-50 whitespace-nowrap">Enable</button>
                            @endif
                        </div>
                    </div>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                No organizations found. <a href="{{ route('super-admin.organizations.create') }}" class="text-blue-600 hover:text-blue-800">Create one</a>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

<!-- Pagination -->
@if($organizations->hasPages())
<div class="mt-6 pagination">
    {{ $organizations->links() }}
</div>
@endif
