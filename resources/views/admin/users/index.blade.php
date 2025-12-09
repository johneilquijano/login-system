<x-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="flex h-screen">
            <!-- Sidebar Navigation -->
            <div class="w-64 bg-white shadow-lg">
                <div class="h-16 bg-gradient-to-r from-purple-600 to-indigo-600 flex items-center px-6">
                    <h1 class="text-white font-bold text-xl">Admin Panel</h1>
                </div>

                <nav class="mt-6">
                    <a href="{{ route('admin.dashboard') }}" class="px-6 py-3 border-l-4 border-transparent hover:border-gray-300 text-gray-700 hover:bg-gray-50 font-medium flex items-center space-x-3 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h2a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h2a1 1 0 001-1V9m-9 0a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('admin.users.index') }}" class="px-6 py-3 border-l-4 border-purple-600 bg-purple-50 text-purple-700 font-semibold flex items-center space-x-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20a6 6 0 0112 0v2H6v-2z"></path>
                        </svg>
                        <span>Manage Users</span>
                    </a>

                    <!-- <a href="{{ route('dashboard') }}" class="px-6 py-3 border-l-4 border-transparent hover:border-gray-300 text-gray-700 hover:bg-gray-50 font-medium flex items-center space-x-3 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z"></path>
                        </svg>
                        <span>Back to Dashboard</span>
                    </a> -->
                </nav>

                <!-- Profile Section -->
                <div class="absolute bottom-0 w-64 border-t px-4 py-4">
                    <div class="relative">
                        <button onclick="toggleAdminProfileMenu()" class="flex items-center space-x-3 w-full hover:bg-gray-50 p-2 rounded-lg transition">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-600 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-md">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div class="flex-1 text-left">
                                <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-600">{{ ucfirst(Auth::user()->role) }}</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                            </svg>
                        </button>

                        <!-- Dropdown Menu (fixed position, appears above) -->
                        <div id="admin-profile-dropdown" class="hidden fixed bg-white rounded-lg shadow-xl border border-gray-200 z-50 overflow-hidden w-48" style="display: none;">
                            <a href="{{ route('password.form') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                <span>Change Password</span>
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="border-t">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition flex items-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="flex-1 overflow-auto">
                <!-- Top Header -->
                <div class="bg-white shadow">
                    <div class="px-8 py-4 flex justify-between items-center">
                        <h2 class="text-2xl font-bold text-gray-900">Manage Users</h2>
                        <div class="text-sm text-gray-600">
                            Welcome, <span class="font-semibold">{{ Auth::user()->name }}</span>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Users</h3>
                        <a href="{{ route('admin.users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition text-sm">
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
                        <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap-4 items-center flex-wrap">
                            <div class="flex items-center gap-2">
                                <input 
                                    type="text" 
                                    name="search" 
                                    placeholder="Search by name or email..." 
                                    value="{{ request('search') }}"
                                    class="w-80 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 mr-8"
                                >

                                <select 
                                    name="role" 
                                    class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                    <option value="">All Roles</option>
                                    <option value="employee" {{ request('role') === 'employee' ? 'selected' : '' }}>Employee</option>
                                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>

                                <button type="button" id="btn-active" data-active-value="1" class="px-4 py-2 rounded-lg border font-medium text-sm {{ request('active') === '1' ? 'bg-green-600 text-white border-green-600' : 'bg-white text-gray-700' }}">Active</button>
                                <button type="button" id="btn-disabled" data-active-value="0" class="px-4 py-2 rounded-lg border font-medium text-sm {{ request('active') === '0' ? 'bg-red-600 text-white border-red-600' : 'bg-white text-gray-700' }}">Disabled</button>
                            </div>
                        </form>
                    </div>

                    <!-- Users Table (rendered via partial to support AJAX/live search) -->
                    <div id="users-table">
                        @include('admin.users._table')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle admin profile dropdown menu
        function toggleAdminProfileMenu() {
            const dropdown = document.getElementById('admin-profile-dropdown');
            const isHidden = dropdown.style.display === 'none' || !dropdown.style.display;
            
            if (isHidden) {
                const button = event.currentTarget;
                const rect = button.getBoundingClientRect();
                dropdown.style.left = rect.left + 'px';
                dropdown.style.bottom = (window.innerHeight - rect.top) + 'px';
                dropdown.style.display = 'block';
                dropdown.classList.remove('hidden');
            } else {
                dropdown.style.display = 'none';
                dropdown.classList.add('hidden');
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('admin-profile-dropdown');
            if (dropdown && dropdown.style.display === 'block') {
                if (!e.target.closest('#admin-profile-dropdown') && !e.target.closest('button[onclick="toggleAdminProfileMenu()"]')) {
                    dropdown.style.display = 'none';
                    dropdown.classList.add('hidden');
                }
            }
        });

        // Toggle dropdown menu for a specific user id and manage z-index so the menu appears above
        function toggleMenu(id) {
            const menu = document.getElementById('menu-' + id);
            const button = document.querySelector('[data-menu-button="' + id + '"]');
            if (!menu) return;
            const isHidden = menu.classList.contains('hidden');

            // close other open menus and reset button z-index
            document.querySelectorAll('[id^="menu-"]') .forEach(el => {
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
                if (button) button.style.zIndex = 1; // ensure button is behind menu
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
            document.querySelectorAll('[id^="menu-"]') .forEach(el => {
                el.classList.add('hidden');
                el.style.zIndex = '';
                const bid = el.id.replace('menu-', '');
                const b = document.querySelector('[data-menu-button="' + bid + '"]');
                if (b) b.style.zIndex = '';
            });
        });

        // --- Live search and AJAX pagination ---
        function debounce(fn, delay = 300) {
            let t;
            return function(...args) {
                clearTimeout(t);
                t = setTimeout(() => fn.apply(this, args), delay);
            };
        }

        async function fetchUsers(url) {
            const resp = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (!resp.ok) return;
            const html = await resp.text();
            const container = document.getElementById('users-table');
            if (container) container.innerHTML = html;
            attachPaginationHandlers();
        }

        function buildQueryUrl(baseUrl) {
            const search = document.querySelector('input[name="search"]')?.value || '';
            const role = document.querySelector('select[name="role"]')?.value || '';
            const active = window.currentActive || '';
            const params = new URLSearchParams();
            if (search) params.set('search', search);
            if (role) params.set('role', role);
            if (active !== '') params.set('active', active);
            const qs = params.toString();
            return qs ? baseUrl + '?' + qs : baseUrl;
        }

        const debouncedFetch = debounce(() => {
            const url = buildQueryUrl("{{ route('admin.users.index') }}");
            fetchUsers(url);
        }, 300);

        // attach handlers to inputs
        const searchInput = document.querySelector('input[name="search"]');
        const roleSelect = document.querySelector('select[name="role"]');
        if (searchInput) searchInput.addEventListener('input', debouncedFetch);
        if (roleSelect) roleSelect.addEventListener('change', debouncedFetch);

        // active/disabled toggle buttons
        window.currentActive = "{{ request('active', '') }}";
        const btnActive = document.getElementById('btn-active');
        const btnDisabled = document.getElementById('btn-disabled');

        function updateActiveButtonsUi() {
            if (!btnActive || !btnDisabled) return;
            if (window.currentActive === '1') {
                btnActive.classList.add('bg-green-600','text-white','border-green-600');
                btnActive.classList.remove('bg-white','text-gray-700');
                btnDisabled.classList.remove('bg-red-600','text-white','border-red-600');
                btnDisabled.classList.add('bg-white','text-gray-700');
            } else if (window.currentActive === '0') {
                btnDisabled.classList.add('bg-red-600','text-white','border-red-600');
                btnDisabled.classList.remove('bg-white','text-gray-700');
                btnActive.classList.remove('bg-green-600','text-white','border-green-600');
                btnActive.classList.add('bg-white','text-gray-700');
            } else {
                btnActive.classList.remove('bg-green-600','text-white','border-green-600');
                btnActive.classList.add('bg-white','text-gray-700');
                btnDisabled.classList.remove('bg-red-600','text-white','border-red-600');
                btnDisabled.classList.add('bg-white','text-gray-700');
            }
        }

        function setActiveFilter(val) {
            // toggle: if clicked same value, clear filter
            if (window.currentActive === String(val)) {
                window.currentActive = '';
            } else {
                window.currentActive = String(val);
            }
            updateActiveButtonsUi();
            debouncedFetch();
        }

        if (btnActive) btnActive.addEventListener('click', () => setActiveFilter(btnActive.dataset.activeValue));
        if (btnDisabled) btnDisabled.addEventListener('click', () => setActiveFilter(btnDisabled.dataset.activeValue));

        // prevent the search form from full page submit
        const searchForm = document.querySelector('form[method="GET"][action="{{ route('admin.users.index') }}"]');
        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                debouncedFetch();
            });
        }

        // hijack pagination links inside the users table so they use AJAX
        function attachPaginationHandlers() {
            const container = document.getElementById('users-table');
            if (!container) return;
            container.querySelectorAll('.pagination a').forEach(a => {
                a.addEventListener('click', function(e) {
                    e.preventDefault();
                    const href = this.getAttribute('href');
                    if (!href) return;
                    fetchUsers(href);
                });
            });
        }

        // attach on initial load
        attachPaginationHandlers();
    </script>
</x-layout>