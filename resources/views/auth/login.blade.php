<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ config('app.name') }}</title>
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
            <!-- Card Container with opacity -->
            <div class="card-overlay rounded-lg shadow-lg p-8">
                <!-- Logo -->
                <div class="text-center mb-8">
                    <img src="{{ asset('assets/img/logo/logo-green.png') }}" 
                         alt="WifiFromWaste Logo" 
                         class="h-16 mx-auto">
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email input -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-brand-green focus:border-brand-green"
                               value="{{ old('email') }}" 
                               required 
                               autocomplete="email" 
                               placeholder="Enter your email"
                               autofocus>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password input -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="relative mt-1">
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-brand-green focus:border-brand-green"
                                   required 
                                   autocomplete="current-password"
                                   placeholder="Enter your password">
                            <button type="button" 
                                    onclick="togglePassword()"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700">
                                <i class="far fa-eye" id="togglePassword"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   name="remember" 
                                   id="remember" 
                                   class="h-4 w-4 text-brand-green focus:ring-brand-green border-gray-300 rounded"
                                   {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember" class="ml-2 text-sm text-gray-600">
                                Remember me
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" 
                               class="text-sm text-brand-green hover:opacity-80">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-brand-green hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-green">
                        Sign in
                    </button>

                    <!-- Register Link -->
                    @if (Route::has('register'))
                        <p class="text-center text-sm text-gray-600">
                            Don't have an account? 
                            <a href="{{ route('register') }}" class="text-brand-green font-semibold hover:opacity-80">
                                Register here
                            </a>
                        </p>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const toggleIcon = document.getElementById('togglePassword');
            
            if (password.type === 'password') {
                password.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>