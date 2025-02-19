<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - {{ config('app.name', 'Laravel') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .eco-gradient {
            background: linear-gradient(135deg, #4ade80 0%, #22c55e 100%);
        }
        .leaf-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 0c16.569 0 30 13.431 30 30 0 16.569-13.431 30-30 30C13.431 60 0 46.569 0 30 0 13.431 13.431 0 30 0zm0 8c-12.15 0-22 9.85-22 22s9.85 22 22 22 22-9.85 22-22-9.85-22-22-22z' fill='%234ade80' fill-opacity='0.05'/%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="bg-gradient-to-br from-green-50 to-emerald-100">
    <div class="min-h-screen flex flex-col justify-center items-center leaf-pattern">
        <div class="text-center p-8 bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-green-100">
            <i class="fas fa-recycle text-5xl text-green-600 mb-6"></i>
            <h1 class="text-5xl font-bold text-green-800 mb-4">Welcome to Wifi From Waste</h1>
            <p class="text-lg text-green-600 mb-8">Turn Your Waste into WiFi - Making the World Greener!</p>
            
            <div class="space-x-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('WifiFromWaste') }}" class="inline-block eco-gradient text-white px-8 py-4 rounded-lg font-semibold hover:shadow-lg transition-all duration-300">
                            <i class="fas fa-dashboard mr-2"></i>Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-block eco-gradient text-white px-8 py-4 rounded-lg font-semibold hover:shadow-lg transition-all duration-300">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-block bg-white text-green-600 border-2 border-green-500 px-8 py-4 rounded-lg font-semibold hover:bg-green-50 transition-all duration-300">
                                <i class="fas fa-user-plus mr-2"></i>Register
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </div>
</body>
</html>
