<x-layout>
    <div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-blue-50 to-indigo-100">
        <div class="w-full max-w-md">
            <!-- Logo Area -->
            <div class="text-center mb-6">
                <div class="mx-auto inline-flex items-center justify-center w-20 h-20 rounded-3xl shadow-sm mb-4">
                    <div class="w-14 h-14 rounded-lg flex items-center justify-center text-white" style="background-color:#3c83f6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5A2.5 2.5 0 015.5 5h13A2.5 2.5 0 0121 7.5V9a2 2 0 01-2 2h-1v6.5A2.5 2.5 0 0115.5 20h-7A2.5 2.5 0 016 17.5V11H5a2 2 0 01-2-2V7.5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 9V7a2 2 0 012-2h4a2 2 0 012 2v2" />
                        </svg>
                    </div>
                </div>
                <h1 class="text-3xl font-extrabold text-gray-900">Welcome Back</h1>
                <p class="text-gray-600 mt-1">Sign in to your employee account</p>
            </div>

            <!-- Login Card -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                @if(session('error'))
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-md">
                        <p class="text-sm text-red-800">{{ session('error') }}</p>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-md">
                        <ul class="text-sm text-red-800 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input aria-label="Email address" type="email" id="email" name="email" required autofocus
                            value="{{ old('email') }}"
                            class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent @error('email') border-red-300 @enderror">
                        @error('email')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <a href="#" style="color:#3c83f6" class="text-sm text-[#3c83f6] hover:text-[#356fd0]">Forgot?</a>
                        </div>
                        <div class="relative">
                            <input aria-label="Password" type="password" id="password" name="password" required
                                class="w-full px-4 py-2 pr-10 border border-gray-200 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent @error('password') border-red-300 @enderror">
                            <button type="button" id="togglePassword" class="absolute text-gray-500 hover:text-gray-700 focus:outline-none" style="top: 10px; right: 10px;">
                                <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember"
                            class="w-4 h-4 text-teal-600 border-gray-300 rounded focus:ring-teal-400">
                        <label for="remember" class="ml-2 text-sm text-gray-600">Remember me</label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" style="background-color:#3c83f6;border:0;" class="w-full bg-[#3c83f6] hover:bg-[#356fd0] text-white font-semibold py-2 px-4 rounded-lg transition duration-200 mt-2 shadow-sm">
                        Sign In
                    </button>
                </form>

                <!-- Small footer links -->
                <div class="mt-6 text-center text-sm text-gray-600">
                    <p>
                        Don't have an account?
                        <a href="{{ route('register') }}" class="text-teal-600 hover:text-teal-700 font-medium">Sign up</a>
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <p class="text-center text-gray-600 text-sm mt-6">&copy; {{ date('Y') }} Employee Portal. All rights reserved.</p>
        </div>
    </div>

    <script>
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        togglePassword.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Toggle input type between password and text
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            
            // Update icon: eye open for text, eye with slash for password
            if (isPassword) {
                // Show eye-off icon when password is visible
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" stroke-width="2"/>';
            } else {
                // Show eye icon when password is hidden
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
            }
        });
    </script>
</x-layout>
