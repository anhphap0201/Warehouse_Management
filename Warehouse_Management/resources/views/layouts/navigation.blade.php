<nav x-data="{ open: false }" class="bg-white dark:bg-slate-800 border-b border-gray-100 dark:border-slate-700 shadow-md">    <!-- Primary Navigation Menu -->
    <div class="container-70 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 group">
                        <x-application-logo class="block h-8 w-8 object-contain transition duration-300 group-hover:scale-110" />
                        <div class="hidden md:block">
                            <div class="text-lg font-semibold text-gray-800 dark:text-gray-200 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition duration-300">
                                WMS
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 -mt-1">
                                Warehouse
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden lg:flex lg:space-x-2 lg:ml-8">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="px-3 py-2">
                        <i class="fas fa-tachometer-alt mr-1"></i> {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')" class="px-3 py-2">
                        <i class="fas fa-list-ul mr-1"></i> {{ __('Danh mục') }}
                    </x-nav-link>
                    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')" class="px-3 py-2">
                        <i class="fas fa-box mr-1"></i> {{ __('Sản phẩm') }}
                    </x-nav-link>
                    <x-nav-link :href="route('warehouses.index')" :active="request()->routeIs('warehouses.*')" class="px-3 py-2">
                        <i class="fas fa-warehouse mr-1"></i> {{ __('Kho hàng') }}
                    </x-nav-link>
                    <x-nav-link :href="route('stores.index')" :active="request()->routeIs('stores.*')" class="px-3 py-2">
                        <i class="fas fa-store mr-1"></i> {{ __('Cửa hàng') }}
                    </x-nav-link>                    <x-nav-link :href="route('suppliers.index')" :active="request()->routeIs('suppliers.*')" class="px-3 py-2">
                        <i class="fas fa-truck mr-1"></i> {{ __('Nhà cung cấp') }}
                    </x-nav-link>
                    <x-nav-link :href="route('purchase-orders.index')" :active="request()->routeIs('purchase-orders.*')" class="px-3 py-2">
                        <i class="fas fa-file-import mr-1"></i> {{ __('Nhập hàng') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.auto-generation.index')" :active="request()->routeIs('admin.auto-generation.*')" class="px-3 py-2">
                        {{ __('Tự động tạo') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:space-x-4">                <!-- Notifications with bell icon -->
                <div class="notification-container">
                    <a href="{{ route('notifications.index') }}" 
                       class="inline-flex items-center justify-center w-10 h-10 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition ease-in-out duration-150 {{ request()->routeIs('notifications.*') ? 'text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-700' : '' }}">
                        <i class="fas fa-bell text-lg"></i>
                    </a>
                    <span id="notificationDot" class="notification-badge hidden">
                        <span id="notificationCount">0</span>
                    </span>
                </div>

                <!-- User Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-100 dark:hover:bg-slate-700 focus:outline-none transition duration-300 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white dark:bg-slate-800 border-t border-gray-200 dark:border-slate-700">
        <!-- Mobile Brand Section -->
        <div class="px-4 py-3 border-b border-gray-200 dark:border-slate-700">
            <div class="flex items-center space-x-2">
                <x-application-logo class="h-6 w-6 object-contain" />
                <div>
                    <div class="text-sm font-semibold text-gray-800 dark:text-gray-200">
                        Warehouse Management System
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        Hệ thống quản lý kho hàng
                    </div>
                </div>
            </div>
        </div>
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                <i class="fas fa-tachometer-alt mr-2"></i> {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">
                <i class="fas fa-list-ul mr-2"></i> Danh mục
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                <i class="fas fa-box mr-2"></i> Sản phẩm
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('warehouses.index')" :active="request()->routeIs('warehouses.*')">
                <i class="fas fa-warehouse mr-2"></i> Kho hàng
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('stores.index')" :active="request()->routeIs('stores.*')">
                <i class="fas fa-store mr-2"></i> Cửa hàng
            </x-responsive-nav-link>            <x-responsive-nav-link :href="route('suppliers.index')" :active="request()->routeIs('suppliers.*')">
                <i class="fas fa-truck mr-2"></i> Nhà cung cấp
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('purchase-orders.index')" :active="request()->routeIs('purchase-orders.*')">
                <i class="fas fa-file-import mr-2"></i> Nhập hàng
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.auto-generation.index')" :active="request()->routeIs('admin.auto-generation.*')">
                Tự động tạo
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <!-- Mobile Notifications -->
            <div class="px-4 mb-3">
                <a href="{{ route('notifications.index') }}" 
                   class="inline-flex items-center px-3 py-2 text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150 {{ request()->routeIs('notifications.*') ? 'text-gray-900 dark:text-gray-100' : '' }}">
                    <i class="fas fa-bell text-lg mr-2"></i>                    <span>Thông báo</span>
                    <span id="mobileNotificationDot" class="inline-flex items-center justify-center w-5 h-5 ml-2 text-xs font-bold text-white bg-red-600 rounded-full hidden">
                        <span id="mobileNotificationCount">0</span>
                    </span>
                </a>
            </div>
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
