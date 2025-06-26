import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Đảm bảo Alpine được khởi tạo một cách đáng tin cậy
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        Alpine.start();
    });
} else {
    // DOM đã ready
    Alpine.start();
}
