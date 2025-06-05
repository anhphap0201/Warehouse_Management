<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        @stack('styles')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mx-4 mt-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mx-4 mt-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        
        <!-- Real-time notification check -->
        <script>
        function updateNotificationCount() {
            fetch('{{ route("api.notifications.unread-count") }}')
                .then(response => response.json())
                .then(data => {
                    const count = data.count;
                    const dot = document.getElementById('notificationDot');
                    const countSpan = document.getElementById('notificationCount');
                    const mobileDot = document.getElementById('mobileNotificationDot');
                    const mobileCountSpan = document.getElementById('mobileNotificationCount');
                    
                    if (count > 0) {
                        dot.classList.remove('hidden');
                        mobileDot.classList.remove('hidden');
                        countSpan.textContent = count;
                        mobileCountSpan.textContent = count;
                    } else {
                        dot.classList.add('hidden');
                        mobileDot.classList.add('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error fetching notification count:', error);
                });
        }

        // Update notification count on page load and every 30 seconds
        document.addEventListener('DOMContentLoaded', function() {
            updateNotificationCount();
            setInterval(updateNotificationCount, 30000); // 30 seconds
        });
        </script>
        
        @stack('scripts')
    </body>
</html>
