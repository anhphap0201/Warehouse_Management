<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Warehouse Management') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700&display=swap" rel="stylesheet" />
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        @stack('styles')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-50 dark:bg-slate-900 flex flex-col">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-slate-800 shadow-sm">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset
            
            @hasSection('header')
                <header class="bg-white dark:bg-slate-800 shadow-sm">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                        @yield('header')
                    </div>
                </header>
            @endif

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 mx-auto max-w-7xl mt-4 rounded shadow-sm flex items-center" role="alert">
                    <i class="fas fa-check-circle mr-3 text-emerald-500"></i>
                    <span class="block sm:inline font-medium">{{ session('success') }}</span>
                </div>
            @endif
            
            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mx-auto max-w-7xl mt-4 rounded shadow-sm flex items-center" role="alert">
                    <i class="fas fa-exclamation-circle mr-3 text-red-500"></i>
                    <span class="block sm:inline font-medium">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Page Content -->
            <main class="flex-grow py-6">
                @hasSection('content')
                    @yield('content')
                @else
                    {{ $slot ?? '' }}
                @endif
            </main>
            
            <!-- Footer -->
            <footer class="bg-white dark:bg-slate-800 py-4 border-t border-gray-200 dark:border-slate-700 shadow-inner mt-auto">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Warehouse Management System © {{ date('Y') }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Version 1.0
                        </div>
                    </div>
                </div>
            </footer>
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
