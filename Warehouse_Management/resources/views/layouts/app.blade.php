<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Warehouse Management') }} - Hệ thống quản lý kho hàng</title>
        
        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('warehouse-favicon.png') }}">
        <link rel="alternate icon" href="{{ asset('favicon.png') }}">
        <link rel="alternate icon" href="{{ asset('favicon.ico') }}">

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
        <div class="min-h-screen bg-gray-50 dark:bg-slate-900 flex flex-col">            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-slate-800 shadow-sm">
                    <div class="container-70 py-3 sm:py-4 px-2 sm:px-4 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset
            
            @hasSection('header')
                <header class="bg-white dark:bg-slate-800 shadow-sm">
                    <div class="container-70 py-3 sm:py-4 px-2 sm:px-4 lg:px-8">
                        @yield('header')
                    </div>
                </header>
            @endif            <!-- Flash Messages -->
            @if(session('success'))
                <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-3 sm:p-4 container-70 mt-2 sm:mt-4 mx-2 sm:mx-auto rounded shadow-sm flex items-start sm:items-center" role="alert">
                    <i class="fas fa-check-circle mr-2 sm:mr-3 text-emerald-500 flex-shrink-0 mt-0.5 sm:mt-0"></i>
                    <span class="block text-sm sm:text-base font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-3 sm:p-4 container-70 mt-2 sm:mt-4 mx-2 sm:mx-auto rounded shadow-sm flex items-start sm:items-center" role="alert">
                    <i class="fas fa-exclamation-circle mr-2 sm:mr-3 text-red-500 flex-shrink-0 mt-0.5 sm:mt-0"></i>
                    <span class="block text-sm sm:text-base font-medium">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Page Content -->
            <main class="flex-grow py-4 sm:py-6 container-70 px-2 sm:px-4 lg:px-8">
                @hasSection('content')
                    @yield('content')
                @else
                    {{ $slot ?? '' }}
                @endif
            </main>
              <!-- Footer -->
            <footer class="bg-white dark:bg-slate-800 py-3 sm:py-4 border-t border-gray-200 dark:border-slate-700 shadow-inner mt-auto">
                <div class="container-70 px-2 sm:px-4 lg:px-8">
                    <div class="flex flex-col sm:flex-row justify-between items-center space-y-2 sm:space-y-0">
                        <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 text-center sm:text-left">
                            Warehouse Management System © {{ date('Y') }}
                        </div>
                        <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">
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
                    
                    // Cập nhật thông báo máy tính để bàn
                    if (count > 0) {
                        if (dot) {
                            dot.classList.remove('hidden');
                            dot.classList.add('animate-pulse');
                        }
                        if (countSpan) {
                            countSpan.textContent = count > 99 ? '99+' : count;
                        }
                    } else {
                        if (dot) {
                            dot.classList.add('hidden');
                            dot.classList.remove('animate-pulse');
                        }
                    }
                    
                    // Cập nhật thông báo di động
                    if (count > 0) {
                        if (mobileDot) {
                            mobileDot.classList.remove('hidden');
                            mobileDot.classList.add('animate-pulse');
                        }
                        if (mobileCountSpan) {
                            mobileCountSpan.textContent = count > 99 ? '99+' : count;
                        }
                    } else {
                        if (mobileDot) {
                            mobileDot.classList.add('hidden');
                            mobileDot.classList.remove('animate-pulse');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error fetching notification count:', error);
                });
        }

        // Xử lý click điều hướng để đảm bảo điều hướng đúng cách
        function enhanceNavigation() {
            const navLinks = document.querySelectorAll('a[href]:not([href^="#"]):not([href^="javascript:"]):not([onclick])');
            
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    // Chỉ tiếp tục nếu đây là liên kết điều hướng
                    if (!this.href || this.href.startsWith('#') || this.href.startsWith('javascript:')) {
                        return;
                    }
                    
                    // Ngăn chặn nhiều lần click nhanh
                    if (this.hasAttribute('data-navigating')) {
                        e.preventDefault();
                        return;
                    }
                    
                    // Đánh dấu đang điều hướng
                    this.setAttribute('data-navigating', 'true');
                    
                    // Xóa flag sau một thời gian ngắn
                    setTimeout(() => {
                        this.removeAttribute('data-navigating');
                    }, 1000);
                }, { passive: false });
            });
        }

        // Cập nhật số lượng thông báo khi tải trang và mỗi 30 giây
        document.addEventListener('DOMContentLoaded', function() {
            updateNotificationCount();
            enhanceNavigation();
            setInterval(updateNotificationCount, 30000); // 30 giây
        });
        </script>
        
        @stack('scripts')
    </body>
</html>
