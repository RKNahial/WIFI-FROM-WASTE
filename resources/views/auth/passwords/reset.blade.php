<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - {{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('assets/img/system-logo.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-green': '#3C8F3A',
                    }
                }
            }
        }
    </script>
    <style>
        .card-overlay {
            background-color: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(5px);
        }
        .bg-leaf {
            background-image: url('{{ asset('assets/img/leaf-bg.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
    </style>
</head>
<body class="bg-leaf">
    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="max-w-md w-full">
            <div class="card-overlay rounded-lg shadow-lg p-8">
                <!-- Logo -->
                <div class="text-center mb-8">
                    <img src="{{ asset('assets/img/logo/logo-green.png') }}" 
                         alt="WifiFromWaste Logo" 
                         class="h-16 mx-auto">
                </div>

                <h2 class="text-2xl font-bold text-center text-gray-700 mb-6">{{ __('Reset Password') }}</h2>

                <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Email input -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Email Address') }}</label>
                        <div class="relative mt-1">
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-brand-green focus:border-brand-green @error('email') border-red-500 @enderror"
                                   value="{{ $email ?? old('email') }}" 
                                   required 
                                   autocomplete="email"
                                   placeholder="Enter your email address"
                                   autofocus>
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password input -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">{{ __('New Password') }}</label>
                        <div class="relative mt-1">
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-brand-green focus:border-brand-green @error('password') border-red-500 @enderror"
                                   required 
                                   autocomplete="new-password"
                                   placeholder="Enter your new password">
                            <button type="button" 
                                    onclick="togglePassword('password')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700">
                                <i class="far fa-eye" id="togglePassword"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password input -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">{{ __('Confirm Password') }}</label>
                        <div class="relative mt-1">
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-brand-green focus:border-brand-green"
                                   required 
                                   autocomplete="new-password"
                                   placeholder="Confirm your new password">
                            <button type="button" 
                                    onclick="togglePassword('password_confirmation')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700">
                                <i class="far fa-eye" id="toggleConfirmPassword"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-brand-green hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-green">
                        {{ __('Reset Password') }}
                    </button>

                    <!-- Back to Login -->
                    <p class="text-center text-sm text-gray-600">
                        Remember your password? 
                        <a href="{{ route('login') }}" class="text-brand-green font-semibold hover:opacity-80">
                            Back to login
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId === 'password' ? 'togglePassword' : 'toggleConfirmPassword');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
