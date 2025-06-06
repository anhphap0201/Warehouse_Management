# Hệ thống Tự động Tạo Yêu cầu Hoàn chỉnh

## Tổng quan
Hệ thống tự động tạo yêu cầu trả hàng và gửi hàng đã được phát triển hoàn chỉnh, thay thế các nút thủ công trước đây bằng một hệ thống thông minh có thể tự động phân tích và tạo yêu cầu dựa trên điều kiện thực tế.

## Các tính năng chính

### 1. Yêu cầu Trả hàng (Return Requests)
- **Tạo ngẫu nhiên**: Tạo yêu cầu trả hàng ngẫu nhiên từ các cửa hàng
- **Tạo thông minh**: Phân tích tồn kho và tạo yêu cầu dựa trên điều kiện thực tế
- **Lập lịch tự động**: Chạy định kỳ theo lịch được cấu hình

### 2. Yêu cầu Gửi hàng (Shipment Requests) - MỚI
- **Tạo ngẫu nhiên**: Tạo yêu cầu gửi hàng ngẫu nhiên từ các cửa hàng
- **Tạo thông minh**: Phân tích nhu cầu và tồn kho thấp để tạo yêu cầu bổ sung
- **Lập lịch tự động**: Chạy định kỳ để đảm bảo cửa hàng luôn có đủ hàng

### 3. Giao diện Quản trị
- Dashboard hiển thị thống kê tổng hợp
- Form điều khiển thủ công các command
- Hiển thị kết quả thực thi real-time
- Thống kê riêng biệt cho cả return và shipment requests

## Cấu trúc File

### Console Commands
```
app/Console/Commands/
├── GenerateRandomReturnRequests.php    # Tạo yêu cầu trả hàng ngẫu nhiên
├── GenerateSmartReturnRequests.php     # Tạo yêu cầu trả hàng thông minh
├── GenerateRandomShipmentRequests.php  # Tạo yêu cầu gửi hàng ngẫu nhiên
└── GenerateSmartShipmentRequests.php   # Tạo yêu cầu gửi hàng thông minh
```

### Controllers
```
app/Http/Controllers/Admin/
└── AutoGenerationController.php        # Controller xử lý admin interface
```

### Views
```
resources/views/admin/auto-generation/
└── index.blade.php                     # Giao diện quản trị chính
```

### Scheduling
```
app/Console/Kernel.php                   # Cấu hình lập lịch tự động
```

## Chi tiết Commands

### Return Request Commands

#### GenerateRandomReturnRequests
```bash
php artisan stores:generate-return-requests [options]

Options:
  --stores=*              : Specific store IDs
  --percentage=30         : Percentage of stores (default: 30%)
  --min-products=1        : Minimum products per request
  --max-products=5        : Maximum products per request
```

#### GenerateSmartReturnRequests
```bash
php artisan stores:smart-return-requests [options]

Options:
  --dry-run                      : Preview mode only
  --min-overstock-days=30        : Days for overstock detection
  --low-turnover-threshold=0.1   : Turnover rate threshold
```

### Shipment Request Commands

#### GenerateRandomShipmentRequests
```bash
php artisan stores:generate-shipment-requests [options]

Options:
  --stores=*              : Specific store IDs
  --percentage=30         : Percentage of stores (default: 30%)
  --min-products=1        : Minimum products per request
  --max-products=5        : Maximum products per request
  --min-quantity=5        : Minimum quantity per product
  --max-quantity=50       : Maximum quantity per product
```

#### GenerateSmartShipmentRequests
```bash
php artisan stores:smart-shipment-requests [options]

Options:
  --dry-run                     : Preview mode only
  --min-shortage-days=7         : Minimum shortage days
  --low-stock-threshold=10      : Low stock threshold
  --demand-multiplier=2         : Demand prediction multiplier
```

## Lập lịch Tự động

### Return Requests Schedule
- **Daily 9:00 AM**: Random return requests (default percentage)
- **Every 4 hours**: Random return requests (10% stores)
- **8:00 AM & 4:00 PM**: Smart return requests
- **Every 6 hours (6,12,18)**: Smart return requests

### Shipment Requests Schedule
- **Daily 10:00 AM**: Random shipment requests (default percentage)
- **Every 6 hours**: Random shipment requests (15% stores)
- **7:00 AM, 1:00 PM, 7:00 PM**: Smart shipment requests
- **Every 3 hours (8,11,14,17)**: Critical stock level checks

## Routes Admin

```php
Route::prefix('admin/auto-generation')->name('admin.auto-generation.')->group(function () {
    Route::get('/', 'index')->name('index');
    
    // Return requests
    Route::post('/random', 'generateRandomRequests')->name('random');
    Route::post('/smart', 'generateSmartRequests')->name('smart');
    
    // Shipment requests
    Route::post('/random-shipment', 'generateRandomShipmentRequests')->name('random-shipment');
    Route::post('/smart-shipment', 'generateSmartShipmentRequests')->name('smart-shipment');
    
    Route::get('/stats', 'getStats')->name('stats');
});
```

## Thống kê Dashboard

### Thống kê tổng hợp
- Tổng số yêu cầu tự động tạo
- Số yêu cầu tạo hôm nay

### Thống kê Return Requests
- Tổng yêu cầu trả hàng
- Yêu cầu trả hàng đang chờ

### Thống kê Shipment Requests
- Tổng yêu cầu gửi hàng
- Yêu cầu gửi hàng đang chờ

## Notification Types

### Return Requests
- Type: `return_request`
- Generation types: `random_return`, `smart_return`

### Shipment Requests
- Type: `receive_request`
- Generation types: `random_shipment`, `smart_shipment`

## Data Structure

### Notification Data Fields
```json
{
    "store_id": "ID cửa hàng",
    "store_name": "Tên cửa hàng",
    "products": [
        {
            "product_id": "ID sản phẩm",
            "product_name": "Tên sản phẩm",
            "product_sku": "SKU",
            "quantity": "Số lượng",
            "unit_price": "Giá đơn vị",
            "total_price": "Tổng giá"
        }
    ],
    "total_value": "Tổng giá trị",
    "product_count": "Số lượng sản phẩm",
    "reason": "Lý do",
    "auto_generated": true,
    "generation_type": "Loại tạo tự động",
    "priority": "Độ ưu tiên",
    "request_date": "Ngày tạo yêu cầu"
}
```

## Tính năng Intelligent Analysis

### Smart Return Requests
- Phân tích sản phẩm tồn kho quá lâu
- Xác định sản phẩm có tỷ lệ quay vòng thấp
- Đề xuất trả về warehouse để tối ưu hóa không gian
- Tính toán giá trị và ưu tiên

### Smart Shipment Requests
- Phân tích tồn kho thấp
- Dự đoán nhu cầu dựa trên patterns
- Xác định sản phẩm cần bổ sung gấp
- Tính toán số lượng tối ưu

## Priority System
- **High**: Sản phẩm hết hàng hoàn toàn hoặc giá trị cao
- **Medium**: Tồn kho thấp hoặc giá trị trung bình
- **Normal**: Bổ sung thường xuyên

## Logging và Monitoring
- Tất cả hoạt động được log đầy đủ
- Tracking success rate và performance
- Error handling và recovery
- Real-time monitoring qua admin dashboard

## Cách sử dụng

### 1. Truy cập Admin Dashboard
```
URL: /admin/auto-generation
```

### 2. Tạo yêu cầu thủ công
- Chọn loại yêu cầu (Return/Shipment)
- Chọn phương thức (Random/Smart)
- Cấu hình parameters
- Thực thi và xem kết quả

### 3. Monitoring
- Kiểm tra thống kê real-time
- Xem command output
- Theo dõi notifications được tạo

## Test Commands

### Test Return Requests
```bash
# Test random return requests
php artisan stores:generate-return-requests --percentage=10

# Test smart return requests (dry-run)
php artisan stores:smart-return-requests --dry-run
```

### Test Shipment Requests
```bash
# Test random shipment requests
php artisan stores:generate-shipment-requests --percentage=10

# Test smart shipment requests (dry-run)
php artisan stores:smart-shipment-requests --dry-run
```

## Kết quả Hoàn thành

✅ **Đã hoàn thành**:
1. Loại bỏ nút thủ công khỏi store detail page
2. Tạo 4 console commands cho cả return và shipment requests
3. Xây dựng hệ thống scheduling tự động
4. Phát triển admin interface đầy đủ tính năng
5. Tích hợp routing và navigation
6. Hệ thống thống kê và monitoring
7. Documentation đầy đủ

✅ **Tính năng mới bổ sung**:
- Shipment request system hoàn chỉnh
- Smart analysis cho cả hai loại request
- Enhanced admin dashboard với 6 stats cards
- Scheduling riêng biệt cho từng loại request
- Priority system và urgency detection
- Comprehensive logging và error handling

## Tóm tắt
Hệ thống tự động tạo yêu cầu đã được hoàn thiện với đầy đủ tính năng cho cả yêu cầu trả hàng và gửi hàng. Hệ thống có khả năng:
- Tự động phân tích và tạo yêu cầu thông minh
- Lập lịch chạy định kỳ
- Quản lý thông qua giao diện admin trực quan
- Monitoring và thống kê real-time
- Xử lý lỗi và logging đầy đủ

Đây là một giải pháp hoàn chỉnh thay thế việc tạo yêu cầu thủ công, giúp tối ưu hóa quản lý tồn kho và logistics giữa warehouse và các cửa hàng.
