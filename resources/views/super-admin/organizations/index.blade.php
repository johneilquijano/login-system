<x-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="flex h-screen">
            <x-super-admin-sidebar />

            <!-- Main Content Area -->
            <div class="flex-1 overflow-auto">
                <!-- Top Header -->
                <div class="bg-white shadow">
                    <div class="px-8 py-4 flex justify-between items-center">
                        <h2 class="text-2xl font-bold text-gray-900">Manage Organizations</h2>
                        <div class="text-sm text-gray-600">
                            All Organizations
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Organizations</h3>
                        <a href="{{ route('super-admin.organizations.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition text-sm">
                            Create Organization
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
                        <form id="filter-form" method="GET" action="{{ route('super-admin.organizations.index') }}" class="flex gap-4 items-center flex-wrap">
                            <div class="flex items-center gap-2">
                                <input 
                                    type="text" 
                                    name="search" 
                                    id="search-input"
                                    placeholder="Search by name, email, or slug..." 
                                    value="{{ request('search') }}"
                                    class="w-80 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 mr-8"
                                >

                                <select 
                                    name="status" 
                                    id="status-filter"
                                    class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                    <option value="">All Status</option>
                                    <option value="active" @if(request('status') === 'active') selected @endif>Active</option>
                                    <option value="inactive" @if(request('status') === 'inactive') selected @endif>Inactive</option>
                                </select>
                            </div>
                        </form>
                    </div>

                    <!-- Organizations Table -->
                    <div id="organizations-container" class="bg-white rounded-lg shadow overflow-visible">
                        @include('super-admin.organizations._table', ['organizations' => $organizations])
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
            const status = document.getElementById('status-filter').value;

            const params = new URLSearchParams();
            if (search) params.append('search', search);
            if (status) params.append('status', status);

            // Show loading indicator
            const container = document.getElementById('organizations-container');
            container.style.opacity = '0.6';
            container.style.pointerEvents = 'none';

            fetch(`{{ route('super-admin.organizations.index') }}?${params.toString()}`, {
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
                window.history.replaceState({}, '', `{{ route('super-admin.organizations.index') }}?${params.toString()}`);
            })
            .catch(error => {
                console.error('Error:', error);
                container.style.opacity = '1';
                container.style.pointerEvents = 'auto';
            });
        }

        // Live search on input
        const searchInput = document.getElementById('search-input');

        if (searchInput) {
            searchInput.addEventListener('input', debounce(function() {
                performAjaxSearch();
            }, 300));
        }

        // Auto-submit on status filter change
        const statusFilter = document.getElementById('status-filter');

        if (statusFilter) {
            statusFilter.addEventListener('change', function() {
                performAjaxSearch();
            });
        }

        // Toggle dropdown menu for a specific organization id
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

        // Delete organization via AJAX
        function deleteOrganization(orgId) {
            if (!confirm('Are you sure you want to delete this organization?')) return;

            fetch(`/super-admin/organizations/${orgId}`, {
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
                    alert('Error deleting organization');
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Disable organization via AJAX
        function disableOrganization(orgId) {
            fetch(`/super-admin/organizations/${orgId}/disable`, {
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
                    alert('Error disabling organization');
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Enable organization via AJAX
        function enableOrganization(orgId) {
            fetch(`/super-admin/organizations/${orgId}/enable`, {
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
                    alert('Error enabling organization');
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</x-layout>
