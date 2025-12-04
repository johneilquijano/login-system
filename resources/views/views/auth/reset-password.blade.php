<x-layout>
    <div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-blue-50 to-indigo-100">
        <div class="w-full max-w-md">
            <!-- Logo Area -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-2xl mb-4">
                    <span class="text-2xl font-bold text-white">EP</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">Reset Password</h1>
                <p class="text-gray-600 mt-2">Enter your new password</p>
            </div>

            <!-- Reset Card -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-red-800 text-sm font-medium mb-2">Error:</p>
                        <p class="text-red-700 text-sm">{{ $errors->first() }}</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.reset') }}" class="space-y-4">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" id="email" name="email" required
                            value="{{ $email }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            readonly>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                        <input type="password" id="password" name="password" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200 mt-6">
                        Reset Password
                    </button>
                </form>

                <!-- Back to Login -->
                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-medium text-sm">
                        Back to Login
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <p class="text-center text-gray-600 text-sm mt-6">
                &copy; {{ date('Y') }} Employee Portal. All rights reserved.
            </p>
        </div>
    </div>
</x-layout>
