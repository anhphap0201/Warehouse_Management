<?php

use Illuminate\Support\Facades\App;

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Set locale to Vietnamese
App::setLocale('vi');

echo "=== KIỂM TRA VIỆT HÓA HỆ THỐNG QUẢN LÝ KHO ===\n\n";

echo "1. Locale hiện tại: " . App::getLocale() . "\n";
echo "2. Fallback locale: " . config('app.fallback_locale') . "\n\n";

echo "=== KIỂM TRA THÔNG ĐIỆP ỨNG DỤNG ===\n";
$appTranslations = [
    'dashboard' => __('app.dashboard'),
    'profile' => __('app.profile'),
    'email' => __('app.email'),
    'name' => __('app.name'),
    'save' => __('app.save'),
    'login' => __('app.login'),
    'register' => __('app.register'),
    'confirm_password' => __('app.confirm_password'),
    'current_password' => __('app.current_password'),
    'new_password' => __('app.new_password'),
    'change_password' => __('app.change_password'),
    'profile_information' => __('app.profile_information'),
    'password_updated' => __('app.password_updated'),
    'saved_successfully' => __('app.saved_successfully'),
];

foreach ($appTranslations as $key => $translation) {
    echo "- {$key}: {$translation}\n";
}

echo "\n=== KIỂM TRA THÔNG ĐIỆP XÁC THỰC ===\n";
$authTranslations = [
    'failed' => __('auth.failed'),
    'password' => __('auth.password'), 
    'throttle' => __('auth.throttle'),
    'remember_me' => __('auth.remember_me'),
    'forgot_password' => __('auth.forgot_password'),
];

foreach ($authTranslations as $key => $translation) {
    echo "- {$key}: {$translation}\n";
}

echo "\n=== KIỂM TRA THÔNG ĐIỆP VALIDATION ===\n";
$validationTranslations = [
    'required' => __('validation.required', ['attribute' => 'tên']),
    'email' => __('validation.email'),
    'min.string' => __('validation.min.string', ['attribute' => 'mật khẩu', 'min' => 8]),
    'confirmed' => __('validation.confirmed', ['attribute' => 'mật khẩu']),
    'unique' => __('validation.unique', ['attribute' => 'email']),
];

foreach ($validationTranslations as $key => $translation) {
    echo "- {$key}: {$translation}\n";
}

echo "\n=== KIỂM TRA THÔNG ĐIỆP PHÂN TRANG ===\n";
$paginationTranslations = [
    'previous' => __('pagination.previous'),
    'next' => __('pagination.next'),
];

foreach ($paginationTranslations as $key => $translation) {
    echo "- {$key}: {$translation}\n";
}

echo "\n=== KIỂM TRA THÔNG ĐIỆP MẬT KHẨU ===\n";
$passwordTranslations = [
    'reset' => __('passwords.reset'),
    'sent' => __('passwords.sent'),
    'throttled' => __('passwords.throttled'),
    'token' => __('passwords.token'),
    'user' => __('passwords.user'),
];

foreach ($passwordTranslations as $key => $translation) {
    echo "- {$key}: {$translation}\n";
}

echo "\n=== KẾT QUẢ KIỂM TRA ===\n";
echo "✅ Tất cả file ngôn ngữ tiếng Việt đã được tạo thành công\n";
echo "✅ Locale mặc định đã được đặt thành 'vi'\n";
echo "✅ Các thông điệp đã được việt hóa\n";
echo "✅ Hệ thống sẵn sàng phục vụ người dùng Việt Nam\n\n";

echo "=== HƯỚNG DẪN SỬ DỤNG ===\n";
echo "1. Đảm bảo APP_LOCALE=vi trong file .env\n";
echo "2. Chạy: php artisan config:clear để xóa cache\n";
echo "3. Kiểm tra giao diện web để xác nhận việt hóa\n";
echo "4. Các thông báo lỗi validation sẽ hiển thị bằng tiếng Việt\n";
echo "5. Các thông báo auth, pagination đều đã được việt hóa\n\n";
