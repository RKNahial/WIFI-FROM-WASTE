<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - {{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('assets/img/system-icon.png') }}" type="image/png">
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
            <!-- Card Container with opacity -->
            <div class="card-overlay rounded-lg shadow-lg p-8">
                <!-- Logo -->
                <div class="text-center mb-8">
                    <img src="{{ asset('assets/img/logo/logo-green.png') }}" 
                         alt="WifiFromWaste Logo" 
                         class="h-16 mx-auto">
                </div>

                <!-- Status Message -->
                @if (session('status'))
                    <div class="mb-4 text-sm font-medium text-green-600 bg-green-50 rounded-md p-4">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="mb-6 text-sm text-gray-600">
                    {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.') }}
                </div>

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf

                    <!-- Email input -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Email Address') }}</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-brand-green focus:border-brand-green @error('email') border-red-500 @enderror"
                               value="{{ old('email') }}" 
                               required 
                               autocomplete="email"
                               placeholder="Enter your email address"
                               autofocus>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-brand-green hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-green">
                        {{ __('Send Password Reset Link') }}
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
</body>
</html>