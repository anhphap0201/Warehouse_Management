# 📦 Hệ Thống Quản Lý Kho

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D8.0-8892BF.svg)](https://php.net)
[![Laravel Version](https://img.shields.io/badge/Laravel-10.x-FF2D20.svg)](https://laravel.com)

Hệ thống quản lý kho toàn diện được xây dựng bằng Laravel và MySQL, được thiết kế để tối ưu hóa các hoạt động quản lý tồn kho, quản lý đơn hàng mua và theo dõi kho hàng cho các doanh nghiệp hiện đại.

## ✨ Tính Năng

### 🛒 Quản Lý Đơn Hàng Mua
- **Chu Trình Đơn Hàng Hoàn Chỉnh**: Tạo, quản lý và theo dõi đơn hàng mua từ lúc tạo đến hoàn thành
- **Tìm Kiếm Sản Phẩm Động**: Tìm kiếm theo thời gian thực hỗ trợ AJAX cho sản phẩm, kho và nhà cung cấp
- **Quy Trình Trạng Thái**: Chuyển đổi trạng thái tự động (Chờ xử lý → Đã xác nhận → Hoàn thành)
- **Chi Tiết Đơn Hàng**: Theo dõi đơn hàng toàn diện với số lượng, giá cả và tổng tiền
- **Tích Hợp Nhà Cung Cấp**: Quản lý nhà cung cấp liền mạch trong đơn hàng mua

### 📦 Quản Lý Tồn Kho
- **Hỗ Trợ Đa Kho**: Quản lý tồn kho trên nhiều địa điểm kho khác nhau
- **Theo Dõi Di Chuyển Kho**: Kiểm toán hoàn chỉnh tất cả các chuyển động tồn kho
- **Cập Nhật Kho Theo Thời Gian Thực**: Điều chỉnh tồn kho tự động dựa trên đơn hàng mua
- **Giám Sát Mức Tồn Kho**: Theo dõi mức tồn kho hiện tại và lịch sử di chuyển
- **Tổ Chức Theo Danh Mục**: Hệ thống phân loại sản phẩm theo cấp bậc

### 🏢 Hoạt Động Cửa Hàng & Kho
- **Quản Lý Cửa Hàng**: Hệ thống quản lý cửa hàng/chi nhánh toàn diện
- **Hoạt Động Kho**: Hoạt động CRUD kho đầy đủ với theo dõi vị trí
- **Chuyển Kho Liên Kho**: Hỗ trợ chuyển kho giữa các địa điểm
- **Tồn Kho Theo Vị Trí**: Theo dõi mức tồn kho theo vị trí kho cụ thể

### 🔍 Chức Năng Tìm Kiếm Nâng Cao
- **Tìm Kiếm Theo Thời Gian Thực**: Tìm kiếm hỗ trợ AJAX trên sản phẩm, kho và nhà cung cấp
- **Nhiều Điểm Tìm Kiếm**: Các điểm API chuyên dụng cho các loại thực thể khác nhau
- **Lọc Tìm Kiếm**: Tùy chọn lọc nâng cao để truy xuất dữ liệu hiệu quả
- **Kết Quả Tức Thì**: Tìm kiếm nhanh, phản hồi với độ trễ tối thiểu

### 👥 Quản Lý Người Dùng & Xác Thực
- **Xác Thực An Toàn**: Hệ thống xác thực dựa trên Laravel
- **Truy Cập Dựa Trên Vai Trò**: Quản lý vai trò người dùng và kiểm soát quyền
- **Quản Lý Phiên**: Xử lý phiên an toàn và quản lý trạng thái người dùng
- **Bảo Mật Mật Khẩu**: Lưu trữ mật khẩu được mã hóa và quy trình đăng nhập an toàn

### 📊 Báo Cáo & Phân Tích
- **Báo Cáo Đơn Hàng Mua**: Báo cáo toàn diện về hoạt động đơn hàng mua
- **Báo Cáo Tồn Kho**: Báo cáo mức tồn kho và phân tích di chuyển
- **Hiệu Suất Nhà Cung Cấp**: Theo dõi giao hàng và các chỉ số hiệu suất của nhà cung cấp
- **Tóm Tắt Tài Chính**: Theo dõi giá trị đơn hàng và phân tích chi phí

## 🚀 Ngăn Xếp Công Nghệ

| Thành Phần | Công Nghệ |
|-----------|------------|
| **Framework Backend** | Laravel 10.x |
| **Ngôn Ngữ** | PHP 8.0+ |
| **Cơ Sở Dữ Liệu** | MySQL 8.0+ |
| **Frontend** | Blade Templates, HTML5, CSS3 |
| **JavaScript** | jQuery, AJAX cho tính năng thời gian thực |
| **Xác Thực** | Laravel Breeze/Sanctum |
| **CSS Framework** | Tailwin |

## 📋 Yêu Cầu Hệ Thống

Đảm bảo môi trường phát triển của bạn đáp ứng các yêu cầu sau:

- **PHP:** Phiên bản `>= 8.0` với các extension cần thiết
- **Composer:** Phiên bản mới nhất ([getcomposer.org](https://getcomposer.org/))
- **Node.js & NPM:** Node.js LTS với NPM ([nodejs.org](https://nodejs.org/))
- **Cơ Sở Dữ Liệu:** MySQL Server (>= 8.0) hoặc MariaDB (>= 10.3)
- **Web Server:** Apache hoặc Nginx (khuyến nghị cho production)
- **Git:** Để kiểm soát phiên bản ([git-scm.com](https://git-scm.com/))

## ⚙️ Cài Đặt

Thực hiện theo các bước sau để cài đặt dự án cục bộ:

### 1. Clone Repository
```bash
git clone https://github.com/anhphap0201/Warehouse_Management.git
cd Warehouse_Management/Warehouse_Management
```

### 2. Cài Đặt Dependencies PHP
```bash
composer install
```

### 3. Cài Đặt Dependencies Node.js
```bash
npm install
```

### 4. Cấu Hình Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 5. Cấu Hình Cơ Sở Dữ Liệu
Chỉnh sửa file `.env` với thông tin đăng nhập cơ sở dữ liệu của bạn:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=warehouse_management
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 6. Migration Cơ Sở Dữ Liệu
```bash
php artisan migrate
php artisan db:seed
```

### 7. Build Frontend Assets
Cho phát triển:
```bash
npm run dev
```

Cho production:
```bash
npm run build
```

### 8. Khởi Động Development Server
```bash
php artisan serve
```

Truy cập ứng dụng tại `http://localhost:8000`

## 🗃️ Cấu Trúc Cơ Sở Dữ Liệu

Hệ thống sử dụng cơ sở dữ liệu quan hệ có cấu trúc tốt với các thực thể chính sau:

### Bảng Cốt Lõi
- **users** - Xác thực người dùng hệ thống và hồ sơ
- **products** - Danh mục sản phẩm với danh mục và thông số kỹ thuật
- **categories** - Phân loại sản phẩm theo cấp bậc
- **warehouses** - Quản lý vị trí và chi tiết kho
- **stores** - Quản lý vị trí cửa hàng/chi nhánh
- **suppliers** - Thông tin nhà cung cấp và chi tiết liên hệ

### Hệ Thống Đơn Hàng Mua
- **purchase_orders** - Bản ghi đơn hàng mua chính với liên kết đến nhà cung cấp
- **purchase_order_items** - Các mặt hàng riêng lẻ trong đơn hàng mua
- **order_statuses** - Theo dõi trạng thái đơn hàng mua

### Quản Lý Tồn Kho
- **stock_movements** - Kiểm toán hoàn chỉnh các thay đổi tồn kho
- **inventory_levels** - Mức tồn kho hiện tại theo kho
- **transfers** - Bản ghi chuyển kho liên kho

### Mối Quan Hệ Chính
- Sản phẩm thuộc về Danh mục (Nhiều-đến-Một)
- Đơn hàng Mua chứa nhiều Mặt hàng (Một-đến-Nhiều)
- Đơn hàng Mua thuộc về Nhà cung cấp (Nhiều-đến-Một)
- Chuyển động Kho theo dõi thay đổi Sản phẩm trên các Kho
- Người dùng có thể tạo và quản lý Đơn hàng Mua

## 🔄 API Endpoints

### Search Endpoints
Hệ thống cung cấp chức năng tìm kiếm theo thời gian thực thông qua các AJAX endpoints:

```
GET /search/products?q={query}          # Tìm kiếm sản phẩm
GET /search/warehouses?q={query}        # Tìm kiếm kho
GET /search/suppliers?q={query}         # Tìm kiếm nhà cung cấp
```

### Quản Lý Đơn Hàng Mua
```
GET    /purchase-orders                 # Liệt kê tất cả đơn hàng mua
POST   /purchase-orders                 # Tạo đơn hàng mua mới
GET    /purchase-orders/{id}            # Xem chi tiết đơn hàng mua
PUT    /purchase-orders/{id}            # Cập nhật đơn hàng mua
DELETE /purchase-orders/{id}            # Xóa đơn hàng mua
POST   /purchase-orders/{id}/confirm    # Xác nhận đơn hàng mua
POST   /purchase-orders/{id}/complete   # Đánh dấu đơn hàng đã hoàn thành
```

### Quản Lý Sản Phẩm
```
GET    /products                        # Liệt kê tất cả sản phẩm
POST   /products                        # Tạo sản phẩm mới
GET    /products/{id}                   # Xem chi tiết sản phẩm
PUT    /products/{id}                   # Cập nhật sản phẩm
DELETE /products/{id}                   # Xóa sản phẩm
```

### Hoạt Động Kho
```
GET    /warehouses                      # Liệt kê tất cả kho
POST   /warehouses                      # Tạo kho mới
GET    /warehouses/{id}                 # Xem chi tiết kho
PUT    /warehouses/{id}                 # Cập nhật kho
DELETE /warehouses/{id}                 # Xóa kho
GET    /warehouses/{id}/stock           # Xem mức tồn kho của kho
```

### Quản Lý Nhà Cung Cấp
```
GET    /suppliers                       # Liệt kê tất cả nhà cung cấp
POST   /suppliers                       # Tạo nhà cung cấp mới
GET    /suppliers/{id}                  # Xem chi tiết nhà cung cấp
PUT    /suppliers/{id}                  # Cập nhật nhà cung cấp
DELETE /suppliers/{id}                  # Xóa nhà cung cấp
```

## 🏗️ Tổng Quan Kiến Trúc

### Kiến Trúc MVC
Hệ thống tuân theo mô hình MVC (Model-View-Controller) của Laravel:

- **Models**: Xử lý logic dữ liệu và tương tác cơ sở dữ liệu
- **Views**: Blade templates để render giao diện người dùng
- **Controllers**: Xử lý yêu cầu người dùng và phối hợp giữa models và views

### Các Thành Phần Chính

#### Controllers
- `PurchaseOrderController` - Quản lý chu trình đơn hàng mua
- `ProductController` - Xử lý các hoạt động CRUD sản phẩm
- `WarehouseController` - Quản lý hoạt động kho
- `StoreController` - Xử lý quản lý cửa hàng/chi nhánh
- `SearchController` - Cung cấp chức năng tìm kiếm theo thời gian thực

#### Models
- `PurchaseOrder` - Quản lý đơn hàng mua với theo dõi trạng thái
- `PurchaseOrderItem` - Các mặt hàng riêng lẻ trong đơn hàng
- `Product` - Danh mục sản phẩm với mối quan hệ danh mục
- `Warehouse` - Quản lý vị trí và công suất kho

#### Triển Khai Tính Năng Chính
- **Tìm Kiếm Theo Thời Gian Thực**: Tìm kiếm hỗ trợ AJAX với jQuery
- **Forms Động**: Tạo đơn hàng mua tương tác
- **Quản Lý Trạng Thái**: Quy trình tự động để xử lý đơn hàng
- **Theo Dõi Tồn Kho**: Ghi log chuyển động kho toàn diện

## 🚀 Triển Khai

### Thiết Lập Production
1. Cấu hình web server để trỏ đến thư mục `public`
2. Đặt quyền file thích hợp:
   ```bash
   chmod -R 755 storage bootstrap/cache
   ```
3. Cấu hình biến môi trường:
   ```bash
   APP_ENV=production
   APP_DEBUG=false
   ```
4. Tối ưu cho production:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   composer install --optimize-autoloader --no-dev
   ```

### Cân Nhắc Bảo Mật
- Giữ file `.env` an toàn và không bao giờ commit vào version control
- Sử dụng HTTPS trong môi trường production
- Thường xuyên cập nhật dependencies để vá lỗ hổng bảo mật
- Triển khai chiến lược backup thích hợp cho cơ sở dữ liệu

## 🧪 Kiểm Thử

Chạy bộ test để đảm bảo chức năng hệ thống:

```bash
# Chạy tất cả tests
php artisan test

# Chạy các loại test cụ thể
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit

# Tạo báo cáo test coverage
php artisan test --coverage
```

## 📚 Tài Liệu

### Tài Liệu Code
- Tất cả controllers bao gồm docblocks toàn diện
- Database migrations tự tài liệu hóa
- API endpoints tuân theo quy ước RESTful

### Hướng Dẫn Người Dùng
- Dashboard admin cung cấp điều hướng trực quan
- Tạo đơn hàng mua bao gồm quy trình từng bước
- Chức năng tìm kiếm cung cấp gợi ý theo thời gian thực
- Theo dõi tồn kho cung cấp lịch sử di chuyển chi tiết

## 🤝 Đóng Góp

Chúng tôi hoan nghênh sự đóng góp từ cộng đồng! Để đóng góp:

1. **Fork** repository
2. **Tạo** một feature branch: `git checkout -b feature/new-feature`
3. **Commit** các thay đổi của bạn: `git commit -m 'Add new feature'`
4. **Push** lên branch: `git push origin feature/new-feature`
5. **Gửi** một Pull Request

### Tiêu Chuẩn Coding
- Tuân theo tiêu chuẩn coding PSR-12 PHP
- Sử dụng tên biến và hàm có ý nghĩa
- Thêm comment thích hợp cho logic phức tạp
- Viết tests cho chức năng mới

### Định Dạng Commit Message
Sử dụng định dạng conventional commits:
- `feat:` cho tính năng mới
- `fix:` cho sửa lỗi
- `docs:` cho thay đổi tài liệu
- `refactor:` cho refactoring code

## 📄 Giấy Phép

Dự án này được cấp phép theo Giấy phép MIT. Xem file [LICENSE](LICENSE) để biết chi tiết.

## 🙋‍♂️ Hỗ Trợ

Để được hỗ trợ và có câu hỏi:
- Tạo một issue trên GitHub
- Kiểm tra tài liệu hiện có
- Xem lại codebase để có ví dụ triển khai

## 📊 Kiến Trúc Hệ Thống & Biểu Đồ UML

Phần này cung cấp các biểu đồ UML toàn diện để giúp hiểu kiến trúc hệ thống, quy trình làm việc và mối quan hệ giữa các thành phần.

### 📋 Biểu Đồ Class
Minh họa cấu trúc của các class, thuộc tính, phương thức và mối quan hệ giữa chúng.

![Class Diagram](./Img/Warehouse_Management_Class_Diagram.svg)

### 🔄 Biểu Đồ Sequence
Hiển thị tương tác giữa các đối tượng theo trình tự thời gian cho các chức năng chính của hệ thống:

#### 1. Sequence Xác Thực Người Dùng
Quản lý quy trình xác thực và ủy quyền người dùng.

![Authentication Sequence](./Img/Warehouse_Management_Authentication_Sequence_Diagram.svg)

#### 2. Sequence Quản Lý Sản Phẩm
Xử lý các hoạt động và quản lý danh mục sản phẩm.

![Product Management Sequence](./Img/Warehouse_Management_Product_Management_Squence_Diagram.svg)

#### 3. Sequence Quản Lý Tồn Kho
Quản lý tồn kho kho và các hoạt động mức tồn kho.

![Inventory Management Sequence](./Img/Warehouse_Management_Inventory_Management_Sequence_Diagram.svg)

#### 4. Sequence Chuyển Kho Tồn Kho
Xử lý chuyển kho giữa các kho và cửa hàng.

![Inventory Transfer Sequence](./Img/Warehouse_Management_Inventory_Transfer_Sequence_Diagram.svg)

#### 5. Sequence Chuyển Động Kho
Theo dõi và quản lý tất cả các hoạt động chuyển động kho.

![Stock Movement Sequence](./Img/Warehouse_Management_Stock_Movement_Sequence_Diagram.svg)

#### 6. Sequence Quản Lý Cửa Hàng
Quản lý hoạt động và cấu hình cửa hàng/chi nhánh.

![Store Management Sequence](./Img/Warehouse_Management_Store_Management_Sequence_Diagram.svg)

#### 7. Sequence Quản Lý Session & Cache
Xử lý phiên người dùng và cơ chế caching.

![Session Cache Management Sequence](./Img/Warehouse_Management_Session_Cache_Management_Sequence_Diagram.svg)

### 🎯 Biểu Đồ Use Case
Mô tả các chức năng chính của hệ thống và tương tác người dùng với các tính năng khác nhau.

![Use Case Diagram](./Img/Warehouse_Management_Use_Case_Diagram.svg)

## 📞 Liên Hệ

- **Người Duy Trì Dự Án**: AnhPhap
- **GitHub**: [https://github.com/anhphap0201](https://github.com/anhphap0201)
- **Repository**: [Warehouse Management System](https://github.com/anhphap0201/Warehouse_Management)

## 📄 Tài Nguyên Bổ Sung

- **Issues**: [Báo cáo lỗi hoặc yêu cầu tính năng](https://github.com/anhphap0201/Warehouse_Management/issues)

---

**Được xây dựng với ❤️ sử dụng Laravel Framework**

> 🚀 **Thành công với việc cài đặt và sử dụng!** Nếu bạn gặp bất kỳ vấn đề nào, đừng ngần ngại tạo một [Issue](https://github.com/anhphap0201/Warehouse_Management/issues) hoặc kiểm tra tài liệu.