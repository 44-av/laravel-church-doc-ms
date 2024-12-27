<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <div class="flex items-center justify-center mb-4">
        <img src="{{ asset('assets/img/logo.png') }}" alt="logo" class="w-16 mx-auto">
        <h1 class="text-2xl font-bold ms-4">St. Michael the Arcanghel Parish Records Information Management System
        </h1>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mt-4">
            <div class="flex items-center border border-gray-300 rounded-md shadow-sm">
                <span class="px-3 text-gray-500">
                    <i class='bx bxs-user'></i>
                </span>
                <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email')"
                    required autofocus autocomplete="username" placeholder="Enter email address" />
            </div>
            <x-input-error :messages="$errors->get('email')" placeholder="Enter email address" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <div class="flex items-center border border-gray-300 rounded-md shadow-sm">
                <span class="px-3 text-gray-500">
                    <i class='bx bxs-lock-alt'></i>
                </span>
                <x-text-input id="password" class="block w-full" type="password" name="password" required
                    autocomplete="current-password" placeholder="Enter password" />
                <span class="px-3 text-gray-500 cursor-pointer" id="togglePassword">
                    <i class='bx bx-show' id="togglePasswordIcon"></i>
                </span>
            </div>
            <x-input-error :messages="$errors->get('password')" placeholder="Enter password" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('register'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    href="{{ route('register') }}">
                    {{ __('Dont have an account?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#togglePassword').on('click', function() {
                const passwordInput = $('#password');
                const togglePasswordIcon = $('#togglePasswordIcon');
                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    togglePasswordIcon.removeClass('bx-show').addClass('bx-hide');
                } else {
                    passwordInput.attr('type', 'password');
                    togglePasswordIcon.removeClass('bx-hide').addClass('bx-show');
                }
            });
        });
    </script>
</x-guest-layout>