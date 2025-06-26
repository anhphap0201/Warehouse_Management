<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Hệ thống Quản lý Kho hàng</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @php $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true); @endphp
        <link rel="stylesheet" href="{{ asset('build/'.$manifest['resources/css/app.css']['file']) }}">
        <script type="module" src="{{ asset('build/'.$manifest['resources/js/app.js']['file']) }}"></script>
    </head>
    <body class="dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">

        <main class="flex flex-col items-center justify-center flex-grow w-full px-6 py-12 text-center lg:max-w-4xl max-w-[335px]">
            <h1 class="mb-4 text-3xl font-semibold sm:text-4xl text-[#1b1b18] dark:text-[#EDEDEC]">
                Hệ thống Quản lý Kho hàng
            </h1>
            <p class="mb-8 text-lg text-[#706f6c] dark:text-[#A1A09A]">
                Giải pháp toàn diện để theo dõi, quản lý và tối ưu hóa hoạt động kho của bạn. <br class="hidden sm:block">
                Đăng nhập hoặc đăng ký để bắt đầu trải nghiệm!
            </p>
            
            @if (Route::has('login'))
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mt-8">
                    @auth
                        <a
                            href="{{ url('/dashboard') }}"
                            class="w-full sm:w-auto px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-lg transition-all duration-300 ease-in-out transform hover:scale-105 shadow-lg hover:shadow-xl"
                        >
                            Bảng điều khiển
                        </a>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="w-full sm:w-auto px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-lg transition-all duration-300 ease-in-out transform hover:scale-105 shadow-lg hover:shadow-xl"
                        >
                            Đăng nhập
                        </a>

                        @if (Route::has('register'))
                            <a
                                href="{{ route('register') }}"
                                class="w-full sm:w-auto px-8 py-3 bg-white hover:bg-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700 text-blue-600 dark:text-blue-400 font-semibold rounded-xl text-lg border-2 border-blue-600 dark:border-blue-400 transition-all duration-300 ease-in-out transform hover:scale-105 shadow-lg hover:shadow-xl"
                            >
                                Đăng ký
                            </a>
                        @endif
                    @endauth
                </div>
            @endif
        </main>

        <footer class="w-full py-8 mt-auto text-center lg:max-w-4xl max-w-[335px]">
            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                &copy; {{ date('Y') }} {{ config('app.name', 'Hệ thống Quản lý Kho hàng') }}.
            </p>
        </footer>
    </body>
</html>
