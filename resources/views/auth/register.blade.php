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
                <h1 class="text-3xl font-extrabold text-gray-900">Create Account</h1>
                <p class="text-gray-600 mt-1">Join our employee management system</p>
            </div>

            <!-- Register Card -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                @if ($errors->any())
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-md">
                        <ul class="text-sm text-red-800 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <input aria-label="Full name" type="text" id="name" name="name" required autofocus
                            value="{{ old('name') }}"
                            class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent @error('name') border-red-300 @enderror">
                        @error('name')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input aria-label="Email address" type="email" id="email" name="email" required
                            value="{{ old('email') }}"
                            class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent @error('email') border-red-300 @enderror">
                        @error('email')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input aria-label="Password" type="password" id="password" name="password" required
                            class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent @error('password') border-red-300 @enderror">
                        @error('password')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <input aria-label="Confirm password" type="password" id="password_confirmation" name="password_confirmation" required
                            class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent">
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" style="background-color:#3c83f6;border:0;" class="w-full bg-[#3c83f6] hover:bg-[#356fd0] text-white font-semibold py-2 px-4 rounded-lg transition duration-200 mt-6 shadow-sm">
                        Create Account
                    </button>
                </form>

                <!-- Small footer links -->
                <div class="mt-6 text-center text-sm text-gray-600">
                    <p>
                        Already have an account?
                        <a href="{{ route('login') }}" class="text-teal-600 hover:text-teal-700 font-medium">Sign in</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-layout>
