<x-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="flex h-screen">
            <x-super-admin-sidebar />

            <!-- Main Content Area -->
            <div class="flex-1 overflow-auto">
                <!-- Top Header -->
                <div class="bg-white shadow">
                    <div class="px-8 py-4 flex justify-between items-center">
                        <h2 class="text-2xl font-bold text-gray-900">Manage Users</h2>
                        <div class="text-sm text-gray-600">
                            All System Users
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Users</h3>
                        <a href="{{ route('super-admin.users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition text-sm">
                            Create User
                        </a>
                    </div>

                    <!-- Success Message -->
                    @if (session('success'))
                    <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex justify-between items-center">
                        <span>{{ session('success') }}</span>
                        <button onclick="this.parentElement.style.display='none'" class="text-green-700 hover:text-green-900">×</button>
                    </div>
                    @endif

                    <!-- Search and Filter -->
                    <div class="bg-white rounded-lg shadow p-6 mb-6">
                        <form id="filter-form" method="GET" action="{{ route('super-admin.users.index') }}" class="flex gap-4 items-center flex-wrap">
                            <div class="flex items-center gap-2">
                                <input 
                                    type="text" 
                                    name="search" 
                                    id="search-input"
                                    placeholder="Search by name or email..." 
                                    value="{{ request('search') }}"
                                    class="w-80 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 mr-8"
                                >

                                <select 
                                    name="organization" 
                                    id="organization-filter"
                                    class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                    <option value="">All Organizations</option>
                                    @foreach($organizations as $org)
                                    <option value="{{ $org->id }}" @if(request('organization') == $org->id) selected @endif>{{ $org->name }}</option>
                                    @endforeach
                                </select>

                                <select 
                                    name="role" 
                                    id="role-filter"
                                    class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                    <option value="">All Roles</option>
                                    <option value="admin" @if(request('role') === 'admin') selected @endif>Admin</option>
                                    <option value="employee" @if(request('role') === 'employee') selected @endif>Employee</option>
                                </select>

                                <button type="button" id="btn-active" data-active-value="1" class="px-4 py-2 rounded-lg border font-medium text-sm @if(request('status') === 'active') bg-green-600 text-white border-green-600 @else bg-white text-gray-700 @endif">Active</button>
                                <button type="button" id="btn-disabled" data-active-value="0" class="px-4 py-2 rounded-lg border font-medium text-sm @if(request('status') === 'disabled') bg-red-600 text-white border-red-600 @else bg-white text-gray-700 @endif">Disabled</button>
                            </div>
                        </form>
                    </div>

                    <!-- Users Table -->
                    <div id="users-container" class="bg-white rounded-lg shadow overflow-visible">
                        @include('super-admin.users._table', ['users' => $users, 'organizations' => $organizations])
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Debounce function for search input
        function debounce(fn, delay = 300) {
            let t;
            return function(...args) {
                clearTimeout(t);
                t = setTimeout(() => fn.apply(this, args), delay);
            };
        }

        // Function to perform AJAX search/filter
        function performAjaxSearch() {
            const search = document.getElementById('search-input').value;
            const organization = document.getElementById('organization-filter').value;
            const role = document.getElementById('role-filter').value;
            const status = new URL(window.location).searchParams.get('status') || '';

            const params = new URLSearchParams();
            if (search) params.append('search', search);
            if (organization) params.append('organization', organization);
            if (role) params.append('role', role);
            if (status) params.append('status', status);

            // Show loading indicator
            const container = document.getElementById('users-container');
            container.style.opacity = '0.6';
            container.style.pointerEvents = 'none';

            fetch(`{{ route('super-admin.users.index') }}?${params.toString()}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.text())
            .then(html => {
                container.innerHTML = html;
                container.style.opacity = '1';
                container.style.pointerEvents = 'auto';
                
                // Update URL without full page reload
                window.history.replaceState({}, '', `{{ route('super-admin.users.index') }}?${params.toString()}`);
            })
            .catch(error => {
                console.error('Error:', error);
                container.style.opacity = '1';
                container.style.pointerEvents = 'auto';
            });
        }

        // Live search on input
        const searchInput = document.getElementById('search-input');
        const filterForm = document.getElementById('filter-form');

        if (searchInput) {
            searchInput.addEventListener('input', debounce(function() {
                performAjaxSearch();
            }, 300));
        }

        // Auto-submit on filter change
        const organizationFilter = document.getElementById('organization-filter');
        const roleFilter = document.getElementById('role-filter');

        if (organizationFilter) {
            organizationFilter.addEventListener('change', function() {
                performAjaxSearch();
            });
        }

        if (roleFilter) {
            roleFilter.addEventListener('change', function() {
                performAjaxSearch();
            });
        }

        // Toggle dropdown menu for a specific user id and manage z-index so the menu appears above
        function toggleMenu(id) {
            const menu = document.getElementById('menu-' + id);
            const button = document.querySelector('[data-menu-button="' + id + '"]');
            if (!menu) return;
            const isHidden = menu.classList.contains('hidden');

            // close other open menus and reset button z-index
            document.querySelectorAll('[id^="menu-"]').forEach(el => {
                if (el !== menu) {
                    el.classList.add('hidden');
                    el.style.zIndex = '';
                    const bid = el.id.replace('menu-', '');
                    const b = document.querySelector('[data-menu-button="' + bid + '"]');
                    if (b) b.style.zIndex = '';
                }
            });

            if (isHidden) {
                menu.classList.remove('hidden');
                menu.style.zIndex = 9999;
                if (button) button.style.zIndex = 1;
            } else {
                menu.classList.add('hidden');
                menu.style.zIndex = '';
                if (button) button.style.zIndex = '';
            }
        }

        // Delete user via AJAX
        function deleteUser(userId) {
            if (!confirm('Are you sure you want to delete this user?')) return;

            fetch(`/super-admin/users/${userId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (response.ok) {
                    performAjaxSearch();
                } else {
                    alert('Error deleting user');
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Disable user via AJAX
        function disableUser(userId) {
            fetch(`/super-admin/users/${userId}/disable`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (response.ok) {
                    performAjaxSearch();
                } else {
                    alert('Error disabling user');
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Enable user via AJAX
        function enableUser(userId) {
            fetch(`/super-admin/users/${userId}/enable`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (response.ok) {
                    performAjaxSearch();
                } else {
                    alert('Error enabling user');
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Close menus when clicking outside
        document.addEventListener('click', function(e) {
            const target = e.target;
            if (target.closest('[id^="menu-"]') || target.closest('[data-menu-button]')) return;
            document.querySelectorAll('[id^="menu-"]').forEach(el => {
                el.classList.add('hidden');
                el.style.zIndex = '';
                const bid = el.id.replace('menu-', '');
                const b = document.querySelector('[data-menu-button="' + bid + '"]');
                if (b) b.style.zIndex = '';
            });
        });

        // Status filter buttons
        document.addEventListener('DOMContentLoaded', function() {
            const btnActive = document.getElementById('btn-active');
            const btnDisabled = document.getElementById('btn-disabled');

            if (btnActive) {
                btnActive.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = new URL(window.location);
                    url.searchParams.set('status', 'active');
                    window.history.replaceState({}, '', url.toString());
                    performAjaxSearch();
                });
            }

            if (btnDisabled) {
                btnDisabled.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = new URL(window.location);
                    url.searchParams.set('status', 'disabled');
                    window.history.replaceState({}, '', url.toString());
                    performAjaxSearch();
                });
            }
        });
    </script>
</x-layout>
