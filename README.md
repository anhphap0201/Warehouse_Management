# 🌐 Website Quản Lý Kho Hàng - Laravel & MySQL 📦

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D8.0-8892BF.svg)](https://php.net)
[![Laravel Version](https://img.shields.io/badge/Laravel-10.x-FF2D20.svg)](https://laravel.com)</br>
Một ứng dụng web quản lý kho hàng được xây dựng bằng PHP với **Laravel Framework** và **MySQL**, giúp theo dõi sản phẩm, quản lý nhập/xuất kho và thống kê hiệu quả.

## Mục lục

* [✨ Tính năng](#-Tính-năng)
* [🚀 Công nghệ sử dụng](#-Công-nghệ-sử-dụng)
* [📋 Yêu cầu hệ thống](#-yêu-cầu-hệ-thống)
* [⚙️ Cài đặt](#️-cài-đặt)
* [🔧 Cấu hình](#-cấu-hình)
* [▶️ Khởi chạy ứng dụng](#️-khởi-chạy-ứng-dụng)
* [🗃️ Cơ sở dữ liệu](#️-cơ-sở-dữ-liệu)
* [📊 Sơ đồ hệ thống](#-sơ-đồ-hệ-thống)
* [🤝 Đóng góp](#-đóng-góp)
* [📄 Giấy phép](#-giấy-phép)
* [📸 Hình ảnh Demo (Tùy chọn)](#-hình-ảnh-demo-tùy-chọn)
## Tính năng

Dự án cung cấp các chức năng cốt lõi cho việc quản lý kho:

* **Quản lý Sản phẩm:** Xem danh sách, chi tiết, tìm kiếm sản phẩm.
* **Quản lý Danh mục:** Phân loại sản phẩm theo danh mục.
* **Quản lý Kho:**
    * Nhập hàng vào kho.
    * Xuất hàng khỏi kho.
    * Theo dõi tồn kho.
* **Quản lý Cửa hàng/Chi nhánh.**
* **Báo cáo & Thống kê:** Các báo cáo cơ bản về nhập/xuất/tồn.
* **Xác thực Người dùng:** Đăng ký, Đăng nhập, Đăng xuất an toàn.

**Chức năng dành cho Quản trị viên (Admin):**

* **CRUD Sản phẩm:** Thêm, sửa, xóa sản phẩm.
* **CRUD Danh mục:** Thêm, sửa, xóa danh mục.
* **Quản lý Người dùng:** Xem danh sách, phân quyền (nếu có).
* **Quản lý Đơn hàng/Phiếu nhập/xuất.**
* _(Các chức năng quản trị khác...)_

## Công nghệ sử dụng

Dự án được xây dựng trên nền tảng các công nghệ hiện đại và phổ biến:

| Hạng mục         | Công nghệ / Ngôn ngữ                                                                |
| :--------------- | :---------------------------------------------------------------------------------- |
| **Backend** | PHP 8.x, Laravel Framework 10.x+                                                    |
| **Frontend** | Blade Templates, HTML5, CSS3, JavaScript (ES6+)                                     |
|                  | _(Tùy chọn)_ Bootstrap 5 / Tailwind CSS, Vue.js / React                            |
| **Cơ sở dữ liệu** | MySQL 5.7+ / MariaDB 10.3+                                                          |
| **Web Server** | Nginx / Apache (Production), PHP Development Server (Development)                 |
| **Quản lý gói** | Composer (PHP), NPM / Yarn (JavaScript)                                             |
| **Công cụ khác** | Git, Shell Script, YAML (Config), JSON (API/Data), Makefile (Tùy chọn - Build tasks) |

## 📋 Yêu cầu hệ thống

Đảm bảo môi trường phát triển của bạn đáp ứng các yêu cầu sau:

* **PHP:** Phiên bản `>= 8.0`.
* **Composer:** Phiên bản mới nhất ([https://getcomposer.org/](https://getcomposer.org/)).
* **Node.js & NPM:** Node.js LTS và NPM đi kèm ([https://nodejs.org/](https://nodejs.org/)).
* **Cơ sở dữ liệu:** MySQL Server (>= 5.7) hoặc MariaDB Server (>= 10.3).
* **Web Server:** Apache hoặc Nginx (khuyến nghị cho production).
* **Git:** Cần thiết để clone và quản lý phiên bản ([https://git-scm.com/](https://git-scm.com/)).

## ⚙️ Cài đặt

Thực hiện các bước sau để cài đặt dự án trên máy cục bộ:

1.  **Clone Repository:**
    Mở terminal và chạy lệnh:
    ```bash
    git clone [https://github.com/anhphap0201/Warehouse_Management.git](https://github.com/anhphap0201/Warehouse_Management.git) Warehouse_Management
    cd Warehouse_Management
    ```

2.  **Cài đặt Dependencies PHP:**
    ```bash
    composer install --no-dev --optimize-autoloader
    # Dùng --no-dev cho production, bỏ đi nếu là môi trường dev
    ```

3.  **Cài đặt Dependencies Node.js:**
    ```bash
    npm install
    ```

4.  **Biên dịch Frontend Assets:**
    * Cho môi trường development (có hot-reload):
        ```bash
        npm run dev
        ```
    * Cho môi trường production (tối ưu hóa):
        ```bash
        npm run build
        ```

5.  **Tạo file môi trường:**
    Sao chép tệp cấu hình mẫu:
    ```bash
    cp .env.example .env
    ```
    *Lưu ý:* File `.env` chứa các thông tin nhạy cảm, **không bao giờ** commit file này lên Git repository công khai.

6.  **Sinh Khóa Ứng Dụng (Generate App Key):**
    Khóa này dùng để mã hóa session và các dữ liệu nhạy cảm khác.
    ```bash
    php artisan key:generate
    ```

## 🔧 Cấu hình

1.  **Mở tệp `.env`** bằng trình soạn thảo văn bản.
2.  **Cấu hình kết nối Cơ sở dữ liệu:**
    Tìm và cập nhật các biến sau với thông tin kết nối của bạn:
    ```dotenv
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1      # Hoặc IP/hostname của DB server
    DB_PORT=3306          # Port của DB server
    DB_DATABASE=warehouse_management # Tên database bạn đã tạo
    DB_USERNAME=root      # Username kết nối DB
    DB_PASSWORD=          # Password kết nối DB (để trống nếu không có)
    ```
3.  **Cấu hình URL ứng dụng:**
    ```dotenv
    APP_URL=http://localhost:8000 # Hoặc URL bạn dùng để truy cập ứng dụng
    ```
4.  **(Tùy chọn)** Cấu hình các dịch vụ khác như Mail, Cache, Queue... trong file `.env` nếu cần.

## ▶️ Khởi chạy ứng dụng

1.  **Tạo cấu trúc Cơ sở dữ liệu:**
    Chạy migrations để tạo các bảng trong database đã cấu hình ở file `.env`:
    ```bash
    php artisan migrate
    ```
    *(Tùy chọn)* Nếu có seeder để tạo dữ liệu mẫu:
    ```bash
    php artisan db:seed # Có thể chỉ định Seeder cụ thể: --class=TenSeeder
    ```

2.  **Khởi động Development Server:**
    Laravel cung cấp một server tích hợp sẵn tiện lợi cho việc phát triển:
    ```bash
    php artisan serve
    ```

3.  **Truy cập ứng dụng:**
    Mở trình duyệt web và truy cập vào địa chỉ được cung cấp bởi lệnh `php artisan serve` (thường là `http://127.0.0.1:8000` hoặc `http://localhost:8000`).

**Lưu ý:** Đối với môi trường production, bạn cần cấu hình web server (Nginx/Apache) để trỏ vào thư mục `public` của dự án. Tham khảo tài liệu chính thức của Laravel về triển khai: [https://laravel.com/docs/deployment](https://laravel.com/docs/deployment)

## 🗃️ Cơ sở dữ liệu

Dự án sử dụng **MySQL/MariaDB**.

* **Cách tạo bảng ưu tiên (khuyến nghị):** Sử dụng Laravel Migrations như mô tả ở bước [Khởi chạy ứng dụng](#️-khởi-chạy-ứng-dụng).
    ```bash
    php artisan migrate
    ```


## 📊 Sơ đồ hệ thống

Các sơ đồ giúp hình dung cấu trúc và luồng hoạt động của hệ thống.

### Sơ đồ lớp (Class Diagram)

Mô tả cấu trúc các lớp, thuộc tính, phương thức và mối quan hệ giữa chúng.

![Sơ đồ lớp](./Img/Warehouse_Management_Class_Diagram.svg)

### Sơ đồ tuần tự (Sequence Diagram)

Mô tả tương tác giữa các đối tượng theo trình tự thời gian cho các chức năng chính của hệ thống:

1. **UserAuthenticationSequence** - Quản lý xác thực người dùng
   ![Sơ đồ xác thực](./Img/Warehouse_Management_Authentication_Sequence_Diagram.svg)

2. **ProductManagementSequence** - Quản lý sản phẩm
   ![Sơ đồ quản lý sản phẩm](./Img/Warehouse_Management_Product_Management_Squence_Diagram.svg)

3. **InventoryManagementSequence** - Quản lý kho và hàng tồn kho
   ![Sơ đồ quản lý tồn kho](./Img/Warehouse_Management_Inventory_Management_Sequence_Diagram.svg)

4. **InventoryTransferSequence** - Chuyển hàng từ kho đến cửa hàng
   ![Sơ đồ chuyển kho](./Img/Warehouse_Management_Inventory_Transfer_Sequence_Diagram.svg)

5. **StockMovementSequence** - Quản lý chuyển động kho
   ![Sơ đồ chuyển động kho](./Img/Warehouse_Management_Stock_Movement_Sequence_Diagram.svg)

6. **StoreManagementSequence** - Quản lý cửa hàng
   ![Sơ đồ quản lý cửa hàng](./Img/Warehouse_Management_Store_Management_Sequence_Diagram.svg)

7. **SessionCacheManagementSequence** - Quản lý phiên làm việc và bộ nhớ đệm
   ![Sơ đồ quản lý phiên làm việc](./Img/Warehouse_Management_Session_Cache_Management_Sequence_Diagram.svg)

### Sơ đồ Use Case (Use Case Diagram)

Mô tả các chức năng chính của hệ thống và sự tương tác của người dùng (actors) với các chức năng đó.

![Sơ đồ chức năng](./Img/Warehouse_Management_Use_Case_Diagram.svg)


## 🤝 Đóng góp

Chúng tôi luôn chào đón sự đóng góp từ cộng đồng! Nếu bạn muốn đóng góp, vui lòng làm theo các bước sau:

1.  **Fork** repository này về tài khoản GitHub của bạn.
2.  **Clone** repository đã fork về máy của bạn.
3.  Tạo một **nhánh mới** cho tính năng hoặc bản sửa lỗi của bạn:
    ```bash
    git checkout -b feature/ten-tinh-nang-moi # Hoặc fix/mo-ta-loi
    ```
4.  Thực hiện các thay đổi và **commit** chúng với message rõ ràng:
    ```bash
    git add .
    git commit -m "feat: Thêm chức năng X" # Hoặc "fix: Sửa lỗi Y"
    # Tham khảo Conventional Commits: [https://www.conventionalcommits.org/](https://www.conventionalcommits.org/)
    ```
5.  **Push** nhánh của bạn lên repository đã fork trên GitHub:
    ```bash
    git push origin feature/ten-tinh-nang-moi
    ```
6.  Mở một **Pull Request (PR)** từ nhánh của bạn vào nhánh `main` (hoặc `develop`) của repository gốc. Cung cấp mô tả chi tiết về những thay đổi trong PR.

Vui lòng đảm bảo code của bạn tuân thủ coding style của dự án (nếu có quy định).

## 📄 Giấy phép

Dự án này được cấp phép dưới giấy phép MIT. Xem chi tiết tại file [LICENSE](LICENSE).

## 📸 Hình ảnh Demo 

__

---

Chúc bạn cài đặt và sử dụng dự án thành công! Nếu có bất kỳ vấn đề gì, đừng ngần ngại tạo [Issue](https://github.com/anhphap0201/Warehouse_Management/issues).