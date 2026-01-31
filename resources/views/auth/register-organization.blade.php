<x-layout>
    <div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-blue-50 to-indigo-100">
        <div class="w-full max-w-4xl">
            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Side: Header + Features -->
                <div class="flex flex-col" style="justify-content: center;">
                    <!-- Header at Top -->
                    <div class="mb-12">
                        <h1 class="text-3xl font-extrabold text-gray-900">Create Your Organization</h1>
                        <p class="text-gray-600 mt-2">Get started with a complete management solution</p>
                    </div>

                    <!-- Features (Hidden on mobile, shown on desktop) -->
                    <div class="hidden lg:flex lg:flex-col">
                        <div class="bg-white/50 backdrop-blur-sm rounded-2xl p-8 space-y-6">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center h-12 w-12 rounded-md" style="background-color:#e0f2fe">
                                        <svg class="h-6 w-6" style="color:#3c83f6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">Manage Your Team</h3>
                                    <p class="text-sm text-gray-600 mt-1">Easily manage employees and team members</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center h-12 w-12 rounded-md" style="background-color:#e0f2fe">
                                        <svg class="h-6 w-6" style="color:#3c83f6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">Document Management</h3>
                                    <p class="text-sm text-gray-600 mt-1">Upload and distribute documents securely</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center h-12 w-12 rounded-md" style="background-color:#e0f2fe">
                                        <svg class="h-6 w-6" style="color:#3c83f6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">Inventory Tracking</h3>
                                    <p class="text-sm text-gray-600 mt-1">Track tools and inventory efficiently</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center h-12 w-12 rounded-md" style="background-color:#e0f2fe">
                                        <svg class="h-6 w-6" style="color:#3c83f6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">Request Management</h3>
                                    <p class="text-sm text-gray-600 mt-1">Manage approvals and requests efficiently</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side: Registration Form -->
                <div class="bg-white rounded-2xl shadow-lg p-8 h-fit mt-4">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Get Started</h2>

                    <!-- Error Summary -->
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex">
                                <svg class="h-5 w-5 text-red-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <h3 class="text-sm font-medium text-red-800">Please fix the following errors:</h3>
                                    <ul class="mt-2 space-y-1 text-sm text-red-700">
                                        @foreach ($errors->all() as $error)
                                            <li>• {{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('register.store') }}" method="POST" class="space-y-4">
                        @csrf

                        <!-- Organization Name -->
                        <div>
                            <label for="organization_name" class="block text-sm font-medium text-gray-700 mb-1">
                                Organization Name
                            </label>
                            <input type="text" name="organization_name" id="organization_name" 
                                class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('organization_name') border-red-300 @enderror transition"
                                value="{{ old('organization_name') }}" placeholder="e.g., Acme Corporation" required>
                            @error('organization_name')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- First Name -->
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">
                                First Name
                            </label>
                            <input type="text" name="first_name" id="first_name"
                                class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('first_name') border-red-300 @enderror transition"
                                value="{{ old('first_name') }}" placeholder="John" required>
                            @error('first_name')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">
                                Last Name
                            </label>
                            <input type="text" name="last_name" id="last_name"
                                class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('last_name') border-red-300 @enderror transition"
                                value="{{ old('last_name') }}" placeholder="Doe" required>
                            @error('last_name')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                Email Address
                            </label>
                            <input type="email" name="email" id="email"
                                class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('email') border-red-300 @enderror transition"
                                value="{{ old('email') }}" placeholder="you@example.com" required>
                            <p class="mt-1 text-xs text-gray-500">This will be your admin login email</p>
                            @error('email')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone (Optional) -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                                Phone <span class="text-gray-400 font-normal">(Optional)</span>
                            </label>
                            <input type="tel" name="phone" id="phone"
                                class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition"
                                value="{{ old('phone') }}" placeholder="+1 (555) 000-0000">
                            @error('phone')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                Password
                            </label>
                            <input type="password" name="password" id="password"
                                class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent @error('password') border-red-300 @enderror transition"
                                placeholder="••••••••" required>
                            <p class="mt-1 text-xs text-gray-500">At least 8 characters</p>
                            @error('password')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Confirmation -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                Confirm Password
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition"
                                placeholder="••••••••" required>
                            @error('password_confirmation')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Terms Checkbox -->
                        <div class="flex items-start pt-2">
                            <input type="checkbox" name="terms" id="terms" 
                                class="h-4 w-4 rounded focus:ring-blue-500 border-gray-300"
                                {{ old('terms') ? 'checked' : '' }}>
                            <label for="terms" class="ml-2 text-sm text-gray-600">
                                I agree to the <a href="#" class="font-medium" style="color:#3c83f6">Terms of Service</a>
                            </label>
                        </div>
                        @error('terms')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <!-- Submit Button -->
                        <button type="submit" 
                            style="background-color:#3c83f6"
                            class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white font-semibold rounded-lg transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mt-4 shadow-sm">
                            Create Organization
                        </button>

                        <!-- Sign In Link -->
                        <p class="text-center text-sm text-gray-600 pt-2">
                            Already have an organization?
                            <a href="{{ route('login') }}" style="color:#3c83f6" class="font-medium hover:opacity-80">
                                Sign in here
                            </a>
                        </p>
                    </form>
                </div>
            </div>

            <!-- Footer -->
            <p class="text-center text-gray-600 text-sm mt-8">&copy; {{ date('Y') }} Employee Portal. All rights reserved.</p>
        </div>
    </div>
</x-layout>
