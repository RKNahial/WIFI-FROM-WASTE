<!DOCTYPE html>
<html lang="en" class="light">
<head>
    @include('layouts.partials.head')
</head>
<body class="bg-slate-100 dark:bg-slate-900 transition-colors duration-200">
    <div class="min-h-screen">
        @include('layouts.partials.navigation')
        @yield('content')
    </div>
    @include('layouts.partials.modals')
    @include('layouts.partials.scripts')
</body>
</html>