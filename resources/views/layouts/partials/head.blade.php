<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ config('app.name') }} | @yield('title', 'Dashboard')</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script>
    tailwind.config = {
        darkMode: 'class',
        theme: {
            extend: {
                colors: {
                    dark: {
                        '50': '#f8fafc',
                        '900': '#0f172a',
                    }
                }
            }
        }
    }
</script>
@include('layouts.partials.styles')