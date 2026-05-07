# Product Management API

A comprehensive RESTful API for e-commerce product management built with Laravel 11, featuring JWT authentication, multi-language support, hierarchical categories, and role-based access control.

## Features

### Core Functionality
-  **Product Management** - Full CRUD operations with images, files, and attributes
-  **Hierarchical Categories** - Nested categories with parent-child relationships
-  **Multi-language Support** - Arabic and English translations (Astrotomic Translatable)
-  **Image Management** - Multiple gallery images per product
-  **File Uploads** - PDF/document attachments for product specifications
-  **Product Attributes** - Unlimited custom attributes (RAM, Color, Storage, etc.)
-  **Search & Filtering** - By category, brand, price range, and text search

### Authentication & Authorization
-  **JWT Authentication** - Secure token-based auth using `php-open-source-saver/jwt-auth`
-  **Dual Authentication** - Separate guards for Users and Admins
-  **User Features** - Register, Login, Profile, Favorites
-  **Admin Dashboard** - Protected CRUD operations for categories and products

### System Architecture
![alt text](<docs/Design/Product Management API Architecture.jpg>)

---

## Tech Stack

- **Framework**: Laravel 11.x
- **PHP**: 8.2+
- **Database**: Sqlite 3.40.1
- **Authentication**: JWT (php-open-source-saver/jwt-auth)
- **Translations**: Astrotomic Translatable
- **Storage**: Laravel Storage (local/public disk)

---

##  Database ERD
[ERD.pdf](docs/Design/Product_Managment_ERD.pdf)

---

## Installation

### Prerequisites
- PHP >= 8.2
- Composer
- Sqlite >= 3.40
- Node.js & NPM (optional, for frontend assets)

### Setup Steps

 **Clone the repository**
   ```bash
   git clone https://github.com/YahiaHelal/Product-Management-API.git
   cd Product-Management-API
   ```

 **Install dependencies**
   ```bash
   composer install
   ```

 **Environment configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   php artisan jwt:secret
   ```

 **Configure database in `.env`**
```env
DB_CONNECTION=sqlite
DB_DATABASE=/database/database.sqlite
```

 **Run migrations and seeders**
   ```bash
   php artisan migrate:fresh --seed
   ```

 **Create storage symlink**
   ```bash
   php artisan storage:link
   ```

 **Start the development server**
   ```bash
   php artisan serve
   ```

   API will be available at `http://localhost:8000`

---

## Database Seeding

The seeders create sample data for testing:

### Category Tree
```
Electronics
├── Computers
│   ├── Laptops
│   ├── Desktops
│   └── Computer Accessories
│       ├── Keyboards
│       └── Mice
├── Mobile Phones
│   ├── Smartphones
│   ├── Tablets
│   └── Phone Accessories
│       ├── Phone Cases
│       └── Chargers
└── Gaming
    ├── Gaming Consoles
    ├── Video Games
    └── Gaming Controllers

Home & Kitchen
├── Furniture
│   ├── Living Room Furniture
│   └── Bedroom Furniture
└── Kitchen Appliances
    ├── Small Appliances
    └── Large Appliances

Fashion
├── Men's Fashion
│   ├── Men's Clothing
│   └── Men's Shoes
└── Women's Fashion
    ├── Women's Clothing
    └── Women's Shoes

Sports & Outdoors
├── Exercise & Fitness
│   ├── Cardio Equipment
│   └── Strength Training
└── Outdoor Recreation
    └── Camping & Hiking
```

### Sample Products
- **10 Laptops** with attributes (RAM, Storage, Screen, Processor, Graphics)
- **15 Smartphones** with attributes (RAM, Storage, Camera, Battery, Color)
- **5 Gaming Consoles** with attributes (Storage, Resolution, Color)
- **20 Random Products** across various categories

### Default Admin Account
- **Email**: `admin@example.com`
- **Password**: `password`

---

## API Documentation

### Base URL
```
http://localhost:8000/api
```

### Authentication
All protected endpoints require a JWT token in the header:
```
Authorization: Bearer {token}
```

---

## Authentication Endpoints

### User Authentication

#### Register User
```http
POST /api/auth/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

#### Login User
```http
POST /api/auth/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123"
}
```

#### Get User Profile
```http
GET /api/auth/profile
Authorization: Bearer {token}
```

#### Logout User
```http
POST /api/auth/logout
Authorization: Bearer {token}
```

#### Refresh Token
```http
POST /api/auth/refresh
Authorization: Bearer {token}
```

---

### Admin Authentication

#### Admin Login
```http
POST /api/admin/login
Content-Type: application/json

{
  "email": "admin@example.com",
  "password": "password"
}
```

#### Admin Profile
```http
GET /api/admin/profile
Authorization: Bearer {admin_token}
```

#### Admin Logout
```http
POST /api/admin/logout
Authorization: Bearer {admin_token}
```

---

## Product Endpoints

### Public Endpoints (No Auth Required)

#### Get All Products
```http
GET /api/products?page=1&per_page=10&search=laptop&brand=Dell&category_id=5&min_price=500&max_price=2000
```

**Query Parameters**:
- `page` - Page number (default: 1)
- `per_page` - Items per page (default: 10)
- `active_only` - Filter by status (true/false)
- `brand` - Filter by brand name
- `category_id` - Filter by category
- `min_price` - Minimum price
- `max_price` - Maximum price
- `search` - Search in product titles

#### Get Single Product
```http
GET /api/products/{id}
```

---

### Admin Endpoints (Requires Admin Token)

#### Create Product
```http
POST /api/admin/products
Authorization: Bearer {admin_token}
Content-Type: multipart/form-data

category_id: 1
sku: SKU001
price: 999.99
sale_price: 799.99
stock: 50
brand: Sony
status: true
main_image: [file]
gallery_images[]: [file1]
gallery_images[]: [file2]
files[]: [manual.pdf]
attributes[0][name]: RAM
attributes[0][value]: 16GB
title[en]: PlayStation 5
title[ar]: بلايستيشن 5
description[en]: Next-gen gaming console
```

#### Update Product
```http
POST /api/admin/products/{id}
Authorization: Bearer {admin_token}
Content-Type: multipart/form-data

_method: PUT
price: 899.99
stock: 100
main_image: [new_file]
delete_gallery_images[]: 1
attributes[0][name]: RAM
attributes[0][value]: 32GB
```

**Note**: Use `POST` with `_method=PUT` for file uploads.

#### Delete Product
```http
DELETE /api/admin/products/{id}
Authorization: Bearer {admin_token}
```

---

## Category Endpoints

### Public Endpoints

#### Get All Categories
```http
GET /api/categories
```

#### Get Single Category
```http
GET /api/categories/{id}
```

---

### Admin Endpoints

#### Create Category
```http
POST /api/admin/categories
Authorization: Bearer {admin_token}
Content-Type: multipart/form-data

parent_id: 1
status: true
image: [file]
name[en]: Laptops
name[ar]: اللابتوبات
```

#### Update Category
```http
POST /api/admin/categories/{id}
Authorization: Bearer {admin_token}
Content-Type: multipart/form-data

_method: PUT
parent_id: 2
status: true
image: [new_file]
name[en]: Gaming Laptops
```

#### Delete Category
```http
DELETE /api/admin/categories/{id}
Authorization: Bearer {admin_token}
```

---

## Favorites Endpoints

### Get User Favorites
```http
GET /api/favorites
Authorization: Bearer {user_token}
```

### Add to Favorites
```http
POST /api/favorites/{product_id}
Authorization: Bearer {user_token}
```

### Remove from Favorites
```http
DELETE /api/favorites/{product_id}
Authorization: Bearer {user_token}
```

---

## Testing

### Using Postman

1. Import the `postman_collection.json` file from the repository
2. Set the `base_url` variable to `http://localhost:8000/api`
3. Login as user or admin to get a token
4. Token is automatically saved to environment variables
5. All subsequent requests use the token automatically

---

---

## 📁 Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/
│   │   │   ├── Admin/
│   │   │   │   └── AuthController.php
│   │   │   ├── AuthController.php
│   │   │   └── FavoritesController.php
│   │   ├── CategoryController.php
│   │   └── ProductController.php
│   └── Requests/
│       ├── Auth/
│       ├── StoreCategoryRequest.php
│       ├── UpdateCategoryRequest.php
│       ├── StoreProductRequest.php
│       └── UpdateProductRequest.php
├── Models/
│   ├── Admin.php
│   ├── Category.php
│   ├── Product.php
│   ├── ProductAttribute.php
│   ├── ProductFile.php
│   ├── ProductImage.php
│   └── User.php
├── Repositories/
│   ├── Interfaces/
│   ├── CategoryRepository.php
│   └── ProductRepository.php
└── Services/
    ├── Interfaces/
    ├── CategoryService.php
    ├── FileService.php
    ├── ImageService.php
    └── ProductService.php
```

---

## Common Issues & Solutions

### Issue: JWT Token Invalid
**Solution**: Regenerate JWT secret
```bash
php artisan jwt:secret --force
php artisan config:clear
```

### Issue: Storage symlink broken
**Solution**: Recreate symlink
```bash
php artisan storage:link
```

### Issue: File uploads fail on PUT requests
**Solution**: Use POST with `_method=PUT` for multipart data

### Issue: Category circular reference error
**Solution**: This is by design. A category cannot be parent of itself or its descendants.

---

## License

This project is open-sourced software licensed under the [MIT license](LICENSE).

---

## Author

**Yahia Helal**
- GitHub: [@YahiaHelal](https://github.com/YahiaHelal)

---

**Last Updated**: May 2026
