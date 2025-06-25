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
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Custom Styling -->
        <link rel="stylesheet" href="{{ asset('css/custom-tables.css') }}">
        <link rel="stylesheet" href="{{ asset('css/custom-background.css') }}">
        
        <!-- Force Light Mode Override -->
        <link rel="stylesheet" href="{{ asset('css/light-mode-force.css') }}">
        
        @stack('styles')
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div class="min-h-screen flex">
            <!-- Sidebar -->
            <div class="hidden lg:flex lg:flex-col lg:w-64 lg:fixed lg:inset-y-0">
                <div class="flex flex-col flex-grow bg-white border-r border-gray-200 pt-5 pb-4 overflow-y-auto">
                    <!-- Logo -->
                    <div class="flex items-center flex-shrink-0 px-6">
                        <a href="{{ route('dashboard') }}" class="flex items-center">
                            <div class="w-8 h-8 flex items-center justify-center mr-3">
                                <i class="fas fa-warehouse text-gray-600 text-sm"></i>
                            </div>
                            <h1 class="text-lg font-bold text-gray-900">WMS</h1>
                        </a>
                    </div>
                    
                    <!-- Navigation -->
                    <nav class="mt-8 flex-1 px-3 space-y-1">
                        <a href="{{ route('dashboard') }}" 
                           class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt sidebar-icon"></i>
                            {{ __('app.dashboard') }}
                        </a>
                        
                        <a href="{{ route('categories.index') }}" 
                           class="sidebar-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                            <i class="fas fa-tags sidebar-icon"></i>
                            {{ __('warehouse.categories') }}
                        </a>
                        
                        <a href="{{ route('products.index') }}" 
                           class="sidebar-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                            <i class="fas fa-box sidebar-icon"></i>
                            {{ __('warehouse.products') }}
                        </a>
                        
                        <a href="{{ route('warehouses.index') }}" 
                           class="sidebar-link {{ request()->routeIs('warehouses.*') ? 'active' : '' }}">
                            <i class="fas fa-warehouse sidebar-icon"></i>
                            {{ __('warehouse.warehouses') }}
                        </a>
                        
                        <a href="{{ route('stores.index') }}" 
                           class="sidebar-link {{ request()->routeIs('stores.*') ? 'active' : '' }}">
                            <i class="fas fa-store sidebar-icon"></i>
                            {{ __('warehouse.stores') }}
                        </a>
                        
                        <a href="{{ route('suppliers.index') }}" 
                           class="sidebar-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                            <i class="fas fa-truck sidebar-icon"></i>
                            {{ __('warehouse.suppliers') }}
                        </a>
                        
                        <a href="{{ route('purchase-orders.index') }}" 
                           class="sidebar-link {{ request()->routeIs('purchase-orders.*') ? 'active' : '' }}">
                            <i class="fas fa-shopping-cart sidebar-icon"></i>
                            {{ __('app.import_goods') }}
                        </a>
                        
                        <a href="{{ route('return-orders.index') }}" 
                           class="sidebar-link {{ request()->routeIs('return-orders.*') ? 'active' : '' }}">
                            <i class="fas fa-undo sidebar-icon"></i>
                            {{ __('Đơn trả hàng') }}
                        </a>
                    </nav>
                </div>
            </div>
            
            <!-- Main content area -->
            <div class="lg:pl-64 flex flex-col flex-1">
                <!-- Top navigation -->
                <div class="relative z-10 flex-shrink-0 flex h-16 bg-white border-b border-gray-200">
                    <!-- Mobile menu button -->
                    <button type="button" class="mobile-menu-btn px-4 border-r border-gray-200 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500 lg:hidden" x-data x-on:click="$dispatch('toggle-sidebar')">>
                        <span class="sr-only">Open sidebar</span>
                        <i class="fas fa-bars w-6 h-6"></i>
                    </button>
                    
                    <!-- User menu -->
                    <div class="flex-1 px-4 flex justify-end">
                        <div class="flex items-center space-x-3">
                            <!-- Notifications -->
                            <a href="{{ route('notifications.index') }}"
                               class="top-nav-link relative inline-flex items-center justify-center p-2 text-gray-500 hover:text-gray-700 rounded-full hover:bg-gray-100 transition duration-150 ease-in-out {{ request()->routeIs('notifications.*') ? 'text-gray-600' : '' }}"
                               title="{{ __('app.notifications') }}">>
                                <i class="fas fa-bell w-5 h-5"></i>
                                
                                <!-- Notification dot -->
                                <span id="notificationDot" class="absolute -top-0.5 -right-0.5 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-black bg-red-500 rounded-full min-w-[1.25rem] h-5 shadow-lg {{ $unreadNotificationsCount > 0 ? 'animate-pulse' : 'hidden' }}">
                                    <span id="notificationCount">{{ $unreadNotificationsCount > 99 ? '99+' : $unreadNotificationsCount }}</span>
                                </span>
                            </a>
                            
                            <!-- Profile dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="max-w-xs bg-white flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" id="user-menu-button">
                                    <span class="sr-only">Open user menu</span>
                                    <div class="h-8 w-8 rounded-full bg-gray-600 flex items-center justify-center">
                                        <span class="text-sm font-medium text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                    </div>
                                </button>
                                
                                <div x-show="open" 
                                     @click.away="open = false"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                                    <div class="px-4 py-2 text-sm text-gray-700 border-b border-gray-100">
                                        <div class="font-medium">{{ Auth::user()->name }}</div>
                                        <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                                    </div>
                                    <a href="{{ route('profile.edit') }}" class="dropdown-link block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('Profile') }}</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-link block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('Log Out') }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Page Heading -->
                @isset($header)
                    <header class="page-header">
                        {{ $header }}
                    </header>
                @endisset
                
                @hasSection('header')
                    <header class="page-header">
                        @yield('header')
                    </header>
                @endif
            

                <!-- Page Content -->
                <main class="flex-1 py-6">
                    @hasSection('content')
                        @yield('content')
                    @else
                        {{ $slot ?? '' }}
                    @endif
                </main>
            </div>
        </div>
        
        <!-- Mobile sidebar overlay -->
        <div x-data="{ open: false }" 
             @toggle-sidebar.window="open = !open"
             x-show="open" 
             class="relative z-40 lg:hidden">
            <div x-show="open" 
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-600 bg-opacity-75"
                 @click="open = false"></div>
            
            <div x-show="open" 
                 x-transition:enter="transition ease-in-out duration-300 transform"
                 x-transition:enter-start="-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in-out duration-300 transform"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full"
                 class="fixed inset-0 flex z-40">
                <div class="relative flex-1 flex flex-col max-w-xs w-full bg-white">
                    <div class="absolute top-0 right-0 -mr-12 pt-2">
                        <button type="button" 
                                class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                                @click="open = false">
                            <span class="sr-only">Close sidebar</span>
                            <i class="fas fa-times h-6 w-6 text-white"></i>
                        </button>
                    </div>
                    
                    <!-- Mobile sidebar content -->
                    <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                        <div class="flex-shrink-0 flex items-center px-4">
                            <div class="w-8 h-8 bg-gray-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-warehouse text-white text-sm"></i>
                            </div>
                            <h1 class="text-lg font-bold text-gray-900">WMS</h1>
                        </div>
                        <nav class="mt-5 px-2 space-y-1">
                            <a href="{{ route('dashboard') }}" 
                               class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                               @click="open = false">
                                <i class="fas fa-tachometer-alt sidebar-icon"></i>
                                {{ __('app.dashboard') }}
                            </a>
                            
                            <a href="{{ route('categories.index') }}" 
                               class="sidebar-link {{ request()->routeIs('categories.*') ? 'active' : '' }}"
                               @click="open = false">
                                <i class="fas fa-tags sidebar-icon"></i>
                                {{ __('warehouse.categories') }}
                            </a>
                            
                            <a href="{{ route('products.index') }}" 
                               class="sidebar-link {{ request()->routeIs('products.*') ? 'active' : '' }}"
                               @click="open = false">
                                <i class="fas fa-box sidebar-icon"></i>
                                {{ __('warehouse.products') }}
                            </a>
                            
                            <a href="{{ route('warehouses.index') }}" 
                               class="sidebar-link {{ request()->routeIs('warehouses.*') ? 'active' : '' }}"
                               @click="open = false">
                                <i class="fas fa-warehouse sidebar-icon"></i>
                                {{ __('warehouse.warehouses') }}
                            </a>
                            
                            <a href="{{ route('stores.index') }}" 
                               class="sidebar-link {{ request()->routeIs('stores.*') ? 'active' : '' }}"
                               @click="open = false">
                                <i class="fas fa-store sidebar-icon"></i>
                                {{ __('warehouse.stores') }}
                            </a>
                            
                            <a href="{{ route('suppliers.index') }}" 
                               class="sidebar-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}"
                               @click="open = false">
                                <i class="fas fa-truck sidebar-icon"></i>
                                {{ __('warehouse.suppliers') }}
                            </a>
                            
                            <a href="{{ route('purchase-orders.index') }}" 
                               class="sidebar-link {{ request()->routeIs('purchase-orders.*') ? 'active' : '' }}"
                               @click="open = false">
                                <i class="fas fa-shopping-cart sidebar-icon"></i>
                                {{ __('app.import_goods') }}
                            </a>
                            
                            <a href="{{ route('return-orders.index') }}" 
                               class="sidebar-link {{ request()->routeIs('return-orders.*') ? 'active' : '' }}"
                               @click="open = false">
                                <i class="fas fa-undo sidebar-icon"></i>
                                {{ __('Đơn trả hàng') }}
                            </a>
                        </nav>
                    </div>
                </div>
                <div class="flex-shrink-0 w-14"></div>
            </div>
        </div>
        
        @stack('scripts')
    </body>
</html>
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
            // Không cần thêm event listener nữa, sẽ dùng event delegation
            console.log('Navigation enhancement loaded');
        }

        // Cập nhật số lượng thông báo khi tải trang và mỗi 30 giây
        document.addEventListener('DOMContentLoaded', function() {
            updateNotificationCount();
            enhanceNavigation();
            setInterval(updateNotificationCount, 30000); // 30 giây
        });

        // Improved navigation handler - chỉ ngăn chặn double click thực sự
        let lastClickTime = 0;
        let lastClickTarget = null;
        
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a[href]');
            
            if (link && !link.hasAttribute('onclick') && !link.href.startsWith('#') && !link.href.startsWith('javascript:')) {
                const now = Date.now();
                
                // Chỉ ngăn chặn nếu click vào cùng link trong vòng 300ms
                if (lastClickTarget === link && (now - lastClickTime) < 300) {
                    e.preventDefault();
                    return false;
                }
                
                // Cập nhật thông tin click cuối
                lastClickTime = now;
                lastClickTarget = link;
                
                // Đảm bảo link hoạt động bình thường
                console.log('Navigating to:', link.href);
            }
        }, { capture: false }); // Không sử dụng capture để tránh conflict với Alpine
        </script>
        
        @stack('scripts')
    </body>
</html>
