/**
 * Warehouse Show Page - Inventory Search and Filter
 * Handles real-time search functionality for warehouse inventory display
 */

class WarehouseInventorySearch {
    constructor() {
        this.searchInput = document.getElementById('searchInput');
        this.searchLoader = document.getElementById('searchLoader');
        this.clearSearch = document.getElementById('clearSearch');
        this.searchResults = document.getElementById('searchResults');
        this.searchResultsText = document.getElementById('searchResultsText');
        this.inventoryTable = document.querySelector('tbody');
        this.tableFooter = document.querySelector('tfoot');
        this.emptyState = document.querySelector('.text-center.py-12');
        
        this.searchTimeout = null;
        this.allInventoryRows = [];
        this.filteredRows = [];
        
        this.init();
    }
    
    init() {
        if (!this.searchInput) return;
        
        this.loadInventoryData();
        this.bindEvents();
        this.updateClearButton();
    }
    
    loadInventoryData() {
        if (!this.inventoryTable) return;
        
        this.allInventoryRows = Array.from(this.inventoryTable.querySelectorAll('tr')).map(row => {
            const productName = row.querySelector('td:nth-child(1) .text-sm.font-medium')?.textContent?.toLowerCase() || '';
            const productDescription = row.querySelector('td:nth-child(1) .text-sm.text-gray-500')?.textContent?.toLowerCase() || '';
            const sku = row.querySelector('td:nth-child(2) .font-mono')?.textContent?.toLowerCase() || '';
            const category = row.querySelector('td:nth-child(3) .inline-flex')?.textContent?.toLowerCase() || '';
            const quantity = row.querySelector('td:nth-child(4) .inline-flex')?.textContent || '';
            
            return {
                element: row,
                searchText: `${productName} ${productDescription} ${sku} ${category}`.trim(),
                productName: productName,
                quantity: quantity
            };
        });
        
        this.filteredRows = [...this.allInventoryRows];
    }
    
    bindEvents() {
        // Search input with debounce
        this.searchInput.addEventListener('input', (e) => {
            const query = e.target.value;
            this.updateClearButton();
            this.debounceSearch(query);
        });
        
        // Clear search button
        this.clearSearch.addEventListener('click', () => {
            this.clearSearchInput();
        });
        
        // Focus/blur effects
        this.searchInput.addEventListener('focus', (e) => {
            e.target.parentElement.classList.add('ring-2', 'ring-blue-500', 'border-blue-500');
        });
        
        this.searchInput.addEventListener('blur', (e) => {
            e.target.parentElement.classList.remove('ring-2', 'ring-blue-500', 'border-blue-500');
        });
    }
    
    debounceSearch(query) {
        if (this.searchTimeout) {
            clearTimeout(this.searchTimeout);
        }
        
        this.searchTimeout = setTimeout(() => {
            this.performSearch(query);
        }, 300);
    }
    
    showLoader() {
        this.searchLoader?.classList.remove('hidden');
    }
    
    hideLoader() {
        this.searchLoader?.classList.add('hidden');
    }
    
    updateClearButton() {
        if (this.searchInput.value.trim()) {
            this.clearSearch?.classList.remove('hidden');
        } else {
            this.clearSearch?.classList.add('hidden');
        }
    }
    
    clearSearchInput() {
        this.searchInput.value = '';
        this.updateClearButton();
        this.performSearch('');
    }
    
    updateSearchResults(query, resultCount, totalCount) {
        if (!this.searchResults || !this.searchResultsText) return;
        
        if (query.trim()) {
            this.searchResults.classList.remove('hidden');
            if (resultCount === 0) {
                this.searchResultsText.textContent = `Không tìm thấy kết quả nào cho "${query}"`;
            } else if (resultCount === totalCount) {
                this.searchResults.classList.add('hidden');
            } else {
                this.searchResultsText.textContent = `Tìm thấy ${resultCount} trong ${totalCount} sản phẩm cho "${query}"`;
            }
        } else {
            this.searchResults.classList.add('hidden');
        }
    }
    
    updateTableFooter() {
        if (!this.tableFooter || this.filteredRows.length === 0) return;
        
        const totalQuantity = this.filteredRows.reduce((sum, row) => {
            const quantityText = row.quantity.replace(/[^\d]/g, '');
            return sum + (parseInt(quantityText) || 0);
        }, 0);
        
        const footerCell = this.tableFooter.querySelector('td:nth-child(2) .inline-flex');
        if (footerCell) {
            footerCell.textContent = `${totalQuantity.toLocaleString()} sản phẩm`;
        }
    }
    
    showEmptyState(query = '') {
        if (!this.emptyState) return;
        
        this.emptyState.style.display = '';
        const title = this.emptyState.querySelector('h3');
        const description = this.emptyState.querySelector('p');
        
        if (query) {
            if (title) title.textContent = 'Không tìm thấy sản phẩm nào';
            if (description) description.textContent = `Không có sản phẩm nào khớp với từ khóa "${query}". Hãy thử từ khóa khác.`;
        }
    }
    
    hideEmptyState() {
        if (this.emptyState) {
            this.emptyState.style.display = 'none';
        }
    }
    
    showInventoryTable() {
        if (this.inventoryTable) {
            this.inventoryTable.parentElement.parentElement.style.display = '';
        }
    }
    
    hideInventoryTable() {
        if (this.inventoryTable) {
            this.inventoryTable.parentElement.parentElement.style.display = 'none';
        }
    }
    
    performSearch(query) {
        if (!this.inventoryTable) return;
        
        this.showLoader();
        
        // Simulate async search with setTimeout
        setTimeout(() => {
            const searchTerms = query.toLowerCase().trim().split(/\s+/).filter(term => term.length > 0);
            
            if (searchTerms.length === 0) {
                // Show all rows
                this.filteredRows = [...this.allInventoryRows];
                this.allInventoryRows.forEach(row => {
                    row.element.style.display = '';
                });
                
                this.hideEmptyState();
                this.showInventoryTable();
            } else {
                // Filter rows based on search terms
                this.filteredRows = this.allInventoryRows.filter(row => {
                    return searchTerms.every(term => row.searchText.includes(term));
                });
                
                // Show/hide rows
                this.allInventoryRows.forEach(row => {
                    const shouldShow = this.filteredRows.includes(row);
                    row.element.style.display = shouldShow ? '' : 'none';
                });
                
                // Handle empty state
                if (this.filteredRows.length === 0) {
                    this.showEmptyState(query);
                    this.hideInventoryTable();
                } else {
                    this.hideEmptyState();
                    this.showInventoryTable();
                }
            }
            
            this.updateSearchResults(query, this.filteredRows.length, this.allInventoryRows.length);
            this.updateTableFooter();
            this.hideLoader();
        }, 100);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    new WarehouseInventorySearch();
});
