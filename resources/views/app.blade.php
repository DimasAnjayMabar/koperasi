<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Travel App</title>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-white dark:bg-gray-900">
    @yield('content')
    <script src="https://unpkg.com/@supabase/supabase-js@2"></script>
    <script>
        // Initialize Supabase and make it globally available
        window.supabase = supabase.createClient(
            '{{ env("VITE_SUPABASE_URL") }}',
            '{{ env("VITE_SUPABASE_KEY") }}'
        );
    </script>

    <!-- Other scripts -->
    <script src="../path/to/flowbite/dist/flowbite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    @stack('scripts')
</body>
</html>