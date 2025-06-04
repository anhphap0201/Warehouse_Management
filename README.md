# 📦 Warehouse Management System

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D8.0-8892BF.svg)](https://php.net)
[![Laravel Version](https://img.shields.io/badge/Laravel-10.x-FF2D20.svg)](https://laravel.com)

A comprehensive warehouse management system built with Laravel and MySQL, designed to streamline inventory operations, purchase order management, and stock tracking for modern businesses.

## ✨ Features

### 🛒 Purchase Order Management
- **Complete Order Lifecycle**: Create, manage, and track purchase orders from creation to completion
- **Dynamic Product Search**: Real-time AJAX-powered search for products, warehouses, and suppliers
- **Status Workflow**: Automated status transitions (Pending → Confirmed → Completed)
- **Order Details**: Comprehensive order tracking with item quantities, prices, and totals
- **Supplier Integration**: Seamless supplier management within purchase orders

### 📦 Inventory Management
- **Multi-Warehouse Support**: Manage inventory across multiple warehouse locations
- **Stock Movement Tracking**: Complete audit trail of all inventory movements
- **Real-time Stock Updates**: Automatic inventory adjustments based on purchase orders
- **Stock Level Monitoring**: Track current stock levels and movement history
- **Category-based Organization**: Hierarchical product categorization system

### 🏢 Store & Warehouse Operations
- **Store Management**: Comprehensive store/branch management system
- **Warehouse Operations**: Full warehouse CRUD operations with location tracking
- **Inter-warehouse Transfers**: Support for stock transfers between locations
- **Location-based Inventory**: Track stock levels by specific warehouse locations

### 🔍 Advanced Search Functionality
- **Real-time Search**: AJAX-powered search across products, warehouses, and suppliers
- **Multiple Search Endpoints**: Dedicated API endpoints for different entity types
- **Search Filtering**: Advanced filtering options for efficient data retrieval
- **Instant Results**: Fast, responsive search with minimal latency

### 👥 User Management & Authentication
- **Secure Authentication**: Laravel-based authentication system
- **Role-based Access**: User role management and permission controls
- **Session Management**: Secure session handling and user state management
- **Password Security**: Encrypted password storage and secure login processes

### 📊 Reporting & Analytics
- **Purchase Order Reports**: Comprehensive reporting on purchase order activities
- **Inventory Reports**: Stock level reports and movement analytics
- **Supplier Performance**: Track supplier delivery and performance metrics
- **Financial Summaries**: Order value tracking and cost analysis

## 🚀 Technology Stack

| Component | Technology |
|-----------|------------|
| **Backend Framework** | Laravel 10.x |
| **Language** | PHP 8.0+ |
| **Database** | MySQL 8.0+ |
| **Frontend** | Blade Templates, HTML5, CSS3 |
| **JavaScript** | jQuery, AJAX for real-time features |
| **Authentication** | Laravel Breeze/Sanctum |
| **CSS Framework** | Bootstrap 5 |
## 📋 System Requirements

Ensure your development environment meets the following requirements:

- **PHP:** Version `>= 8.0` with required extensions
- **Composer:** Latest version ([getcomposer.org](https://getcomposer.org/))
- **Node.js & NPM:** Node.js LTS with NPM ([nodejs.org](https://nodejs.org/))
- **Database:** MySQL Server (>= 8.0) or MariaDB (>= 10.3)
- **Web Server:** Apache or Nginx (recommended for production)
- **Git:** For version control ([git-scm.com](https://git-scm.com/))

## ⚙️ Installation

Follow these steps to install the project locally:

### 1. Clone Repository
```bash
git clone https://github.com/anhphap0201/Warehouse_Management.git
cd Warehouse_Management/Warehouse_Management
```

### 2. Install PHP Dependencies
```bash
composer install
```

### 3. Install Node.js Dependencies
```bash
npm install
```

### 4. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

### 5. Database Configuration
Edit the `.env` file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=warehouse_management
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 6. Database Migration
```bash
php artisan migrate
php artisan db:seed
```

### 7. Build Frontend Assets
For development:
```bash
npm run dev
```

For production:
```bash
npm run build
```

### 8. Start Development Server
```bash
php artisan serve
```

Access the application at `http://localhost:8000`

## 🗃️ Database Schema

The system uses a well-structured relational database with the following key entities:

### Core Tables
- **users** - System user authentication and profiles
- **products** - Product catalog with categories and specifications
- **categories** - Hierarchical product categorization
- **warehouses** - Warehouse location and details management
- **stores** - Store/branch location management
- **suppliers** - Supplier information and contact details

### Purchase Order System
- **purchase_orders** - Main purchase order records
- **purchase_order_items** - Individual items within purchase orders
- **order_statuses** - Purchase order status tracking

### Inventory Management
- **stock_movements** - Complete audit trail of inventory changes
- **inventory_levels** - Current stock levels by warehouse
- **transfers** - Inter-warehouse transfer records

### Key Relationships
- Products belong to Categories (Many-to-One)
- Purchase Orders contain multiple Items (One-to-Many)
- Stock Movements track Product changes across Warehouses
- Users can create and manage Purchase Orders

## 🔄 API Endpoints

### Search Endpoints
The system provides real-time search functionality through AJAX endpoints:

```
GET /search/products?q={query}          # Search products
GET /search/warehouses?q={query}        # Search warehouses  
GET /search/suppliers?q={query}         # Search suppliers
```

### Purchase Order Management
```
GET    /purchase-orders                 # List all purchase orders
POST   /purchase-orders                 # Create new purchase order
GET    /purchase-orders/{id}            # View purchase order details
PUT    /purchase-orders/{id}            # Update purchase order
DELETE /purchase-orders/{id}            # Delete purchase order
POST   /purchase-orders/{id}/confirm    # Confirm purchase order
POST   /purchase-orders/{id}/complete   # Mark order as completed
```

### Product Management
```
GET    /products                        # List all products
POST   /products                        # Create new product
GET    /products/{id}                   # View product details
PUT    /products/{id}                   # Update product
DELETE /products/{id}                   # Delete product
```

### Warehouse Operations
```
GET    /warehouses                      # List all warehouses
POST   /warehouses                      # Create new warehouse
GET    /warehouses/{id}                 # View warehouse details
PUT    /warehouses/{id}                 # Update warehouse
DELETE /warehouses/{id}                 # Delete warehouse
GET    /warehouses/{id}/stock           # View warehouse stock levels
```

## 🏗️ Architecture Overview

### MVC Architecture
The system follows Laravel's MVC (Model-View-Controller) pattern:

- **Models**: Handle data logic and database interactions
- **Views**: Blade templates for user interface rendering
- **Controllers**: Process user requests and coordinate between models and views

### Key Components

#### Controllers
- `PurchaseOrderController` - Manages purchase order lifecycle
- `ProductController` - Handles product CRUD operations
- `WarehouseController` - Manages warehouse operations
- `StoreController` - Handles store/branch management
- `SearchController` - Provides real-time search functionality

#### Models
- `PurchaseOrder` - Purchase order management with status tracking
- `PurchaseOrderItem` - Individual items within orders
- `Product` - Product catalog with category relationships
- `Warehouse` - Warehouse location and capacity management
- `StockMovement` - Inventory movement audit trail

#### Key Features Implementation
- **Real-time Search**: AJAX-powered search with jQuery
- **Dynamic Forms**: Interactive purchase order creation
- **Status Management**: Automated workflow for order processing
- **Inventory Tracking**: Comprehensive stock movement logging

## 🚀 Deployment

### Production Setup
1. Configure your web server to point to the `public` directory
2. Set appropriate file permissions:
   ```bash
   chmod -R 755 storage bootstrap/cache
   ```
3. Configure environment variables:
   ```bash
   APP_ENV=production
   APP_DEBUG=false
   ```
4. Optimize for production:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   composer install --optimize-autoloader --no-dev
   ```

### Security Considerations
- Keep `.env` file secure and never commit to version control
- Use HTTPS in production environments
- Regularly update dependencies for security patches
- Implement proper backup strategies for database

## 🧪 Testing

Run the test suite to ensure system functionality:

```bash
# Run all tests
php artisan test

# Run specific test types
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit

# Generate test coverage report
php artisan test --coverage
```

## 📚 Documentation

### Code Documentation
- All controllers include comprehensive docblocks
- Database migrations are self-documenting
- API endpoints follow RESTful conventions

### User Guide
- Admin dashboard provides intuitive navigation
- Purchase order creation includes step-by-step workflow
- Search functionality offers real-time suggestions
- Inventory tracking provides detailed movement history

## 🤝 Contributing

We welcome contributions from the community! To contribute:

1. **Fork** the repository
2. **Create** a feature branch: `git checkout -b feature/new-feature`
3. **Commit** your changes: `git commit -m 'Add new feature'`
4. **Push** to the branch: `git push origin feature/new-feature`
5. **Submit** a Pull Request

### Coding Standards
- Follow PSR-12 PHP coding standards
- Use meaningful variable and function names
- Add appropriate comments for complex logic
- Write tests for new functionality

### Commit Message Format
Use conventional commits format:
- `feat:` for new features
- `fix:` for bug fixes
- `docs:` for documentation changes
- `refactor:` for code refactoring

## 📄 License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## 🙋‍♂️ Support

For support and questions:
- Create an issue on GitHub
- Check existing documentation
- Review the codebase for implementation examples

## 📊 System Architecture & UML Diagrams

This section provides comprehensive UML diagrams to help understand the system's architecture, workflows, and component relationships.

### 📋 Class Diagram
Illustrates the structure of classes, their attributes, methods, and relationships between them.

![Class Diagram](./Img/Warehouse_Management_Class_Diagram.svg)

### 🔄 Sequence Diagrams
Show interactions between objects in time sequence for key system functionalities:

#### 1. User Authentication Sequence
Manages user authentication and authorization processes.

![Authentication Sequence](./Img/Warehouse_Management_Authentication_Sequence_Diagram.svg)

#### 2. Product Management Sequence
Handles product catalog operations and management.

![Product Management Sequence](./Img/Warehouse_Management_Product_Management_Squence_Diagram.svg)

#### 3. Inventory Management Sequence
Manages warehouse inventory and stock level operations.

![Inventory Management Sequence](./Img/Warehouse_Management_Inventory_Management_Sequence_Diagram.svg)

#### 4. Inventory Transfer Sequence
Handles stock transfers between warehouses and stores.

![Inventory Transfer Sequence](./Img/Warehouse_Management_Inventory_Transfer_Sequence_Diagram.svg)

#### 5. Stock Movement Sequence
Tracks and manages all stock movement operations.

![Stock Movement Sequence](./Img/Warehouse_Management_Stock_Movement_Sequence_Diagram.svg)

#### 6. Store Management Sequence
Manages store/branch operations and configurations.

![Store Management Sequence](./Img/Warehouse_Management_Store_Management_Sequence_Diagram.svg)

#### 7. Session & Cache Management Sequence
Handles user sessions and caching mechanisms.

![Session Cache Management Sequence](./Img/Warehouse_Management_Session_Cache_Management_Sequence_Diagram.svg)

### 🎯 Use Case Diagram
Describes the main system functionalities and user interactions with various features.

![Use Case Diagram](./Img/Warehouse_Management_Use_Case_Diagram.svg)

## 📞 Contact

- **Project Maintainer**: PKA Development Team
- **GitHub**: [https://github.com/anhphap0201](https://github.com/anhphap0201)
- **Repository**: [Warehouse Management System](https://github.com/anhphap0201/Warehouse_Management)

## 📄 Additional Resources

- **Issues**: [Report bugs or request features](https://github.com/anhphap0201/Warehouse_Management/issues)
- **Discussions**: [Community discussions and Q&A](https://github.com/anhphap0201/Warehouse_Management/discussions)
- **Wiki**: [Detailed documentation and guides](https://github.com/anhphap0201/Warehouse_Management/wiki)

---

**Built with ❤️ using Laravel Framework**

> 🚀 **Success with installation and usage!** If you encounter any issues, don't hesitate to create an [Issue](https://github.com/anhphap0201/Warehouse_Management/issues) or check the documentation.