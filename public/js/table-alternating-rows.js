/**
 * Table Alternating Rows JavaScript Helper
 * Đảm bảo alternating rows hoạt động với dynamic content
 */

document.addEventListener('DOMContentLoaded', function() {
    // Hàm áp dụng alternating rows
    function applyAlternatingRows(tableSelector = '.table-modern') {
        const tables = document.querySelectorAll(tableSelector);
        
        tables.forEach(table => {
            const tbody = table.querySelector('tbody');
            if (!tbody) return;
            
            const rows = tbody.querySelectorAll('tr');
            
            rows.forEach((row, index) => {
                // Xóa các class cũ
                row.classList.remove('odd-row', 'even-row');
                
                // Áp dụng class mới
                if (index % 2 === 0) {
                    row.classList.add('odd-row');
                    row.style.backgroundColor = '#f9fafb';
                } else {
                    row.classList.add('even-row');
                    row.style.backgroundColor = '#ffffff';
                }
                
                // Hover effect
                row.addEventListener('mouseenter', function() {
                    if (index % 2 === 0) {
                        this.style.backgroundColor = '#e5e7eb';
                    } else {
                        this.style.backgroundColor = '#f3f4f6';
                    }
                });
                
                row.addEventListener('mouseleave', function() {
                    if (index % 2 === 0) {
                        this.style.backgroundColor = '#f9fafb';
                    } else {
                        this.style.backgroundColor = '#ffffff';
                    }
                });
            });
        });
    }
    
    // Áp dụng khi trang load
    applyAlternatingRows();
    
    // Observer để tự động áp dụng khi table content thay đổi
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList') {
                // Kiểm tra nếu có thêm/xóa row
                const addedNodes = Array.from(mutation.addedNodes);
                const removedNodes = Array.from(mutation.removedNodes);
                
                const hasTableChanges = [...addedNodes, ...removedNodes].some(node => 
                    node.nodeType === 1 && (
                        node.tagName === 'TR' || 
                        node.querySelector && node.querySelector('tr')
                    )
                );
                
                if (hasTableChanges) {
                    setTimeout(() => applyAlternatingRows(), 100);
                }
            }
        });
    });
    
    // Observe table containers
    const tableContainers = document.querySelectorAll('.table-container, .table-wrapper');
    tableContainers.forEach(container => {
        observer.observe(container, {
            childList: true,
            subtree: true
        });
    });
    
    // Export function để có thể call từ bên ngoài
    window.applyAlternatingRows = applyAlternatingRows;
    
    console.log('✅ Table alternating rows helper loaded');
});
