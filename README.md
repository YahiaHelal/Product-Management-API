# Product Management API

A comprehensive RESTful API for e-commerce product management built with Laravel, featuring JWT authentication, multi-language support, hierarchical categories, and role-based access control.

## Features

### Core Functionality
- **Product Management** - Full CRUD operations with images, files, and attributes
- **Hierarchical Categories** - Nested categories with parent-child relationships
- **Multi-language Support** - Arabic and English translations (Astrotomic Translatable)
- **Image Management** - Multiple gallery images per product
- **File Uploads** - PDF/document attachments for product specifications
- **Product Attributes** - Unlimited custom attributes (RAM, Color, Storage, etc.)
- **Search & Filtering** - By category, brand, price range, and text search

### Authentication & Authorization
- **JWT Authentication** - Secure token-based auth using `php-open-source-saver/jwt-auth`
- **Dual Authentication** - Separate guards for Users and Admins
- **User Features** - Register, Login, Profile, Favorites
- **Admin Dashboard** - Protected CRUD operations for categories and products

### System Architecture
![Architecture](<docs/Design/Product Management API Architecture.jpg>)

---

## Tech Stack

- **Framework**: Laravel 13.x
- **PHP**: 8.3+
- **Database**: SQLite (default), MySQL/MariaDB/PostgreSQL supported by Laravel config
- **Authentication**: JWT (`php-open-source-saver/jwt-auth`)
- **Translations**: Astrotomic Translatable
- **Storage**: Laravel Storage (local/public disk)

---

## Database ERD
[ERD.pdf](docs/Design/Product_Managment_ERD.pdf)

---

## Installation

### Prerequisites
- PHP >= 8.3
- Composer
- SQLite >= 3.40 (if using SQLite)
- Node.js & NPM (optional, for building frontend assets)

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/YahiaHelal/Product-Management-API.git
   cd Product-Management-API
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Create environment file**
   ```bash
   cp .env.example .env
   ```

4. **Generate app key and JWT secret**
   ```bash
   php artisan key:generate
   php artisan jwt:secret
   ```

5. **Configure database**

   **Option A (recommended): SQLite**
   - Ensure `.env` has:
     ```env
     DB_CONNECTION=sqlite
     ```
   - Create the SQLite file:
     ```bash
     touch database/database.sqlite
     ```
   - You can leave `DB_DATABASE` unset (Laravel defaults to `database/database.sqlite`), or set an absolute path.

   **Option B: MySQL/MariaDB/PostgreSQL**
   - Set `DB_CONNECTION` and related `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` in `.env`.

6. **Run migrations and seeders**
   ```bash
   php artisan migrate:fresh --seed
   ```

7. **Create storage symlink**
   ```bash
   php artisan storage:link
   ```

8. **(Optional) Build frontend assets**
   ```bash
   npm install
   npm run build
   ```

9. **Start the development server**
   ```bash
   php artisan serve
   ```

API base URL: `http://localhost:8000/api`

---

## Quick Start (Alternative)

You can also run the Composer setup script provided by this project:

```bash
composer run setup
```

Then run:

```bash
php artisan jwt:secret
php artisan migrate:fresh --seed
php artisan storage:link
php artisan serve
```

---

## Default Seeded Admin Account
- **Email**: `admin@example.com`
- **Password**: `password`

---

## Authentication

All protected endpoints require a JWT token in the header:

```http
Authorization: Bearer {token}
```

---

## Main API Routes

### User Authentication
- `POST /api/auth/register`
- `POST /api/auth/login`
- `POST /api/auth/logout` (auth required)
- `GET /api/auth/profile` (auth required)
- `POST /api/auth/refresh` (auth required)

### Admin Authentication
- `POST /api/admin/login`
- `POST /api/admin/logout` (admin auth required)
- `GET /api/admin/profile` (admin auth required)
- `POST /api/admin/refresh` (admin auth required)

### Products
- `GET /api/products`
- `GET /api/products/{product}`
- `POST /api/admin/products` (admin auth required)
- `PUT/PATCH /api/admin/products/{product}` (admin auth required)
- `DELETE /api/admin/products/{product}` (admin auth required)

### Categories
- `GET /api/categories`
- `GET /api/categories/{category}`
- `POST /api/admin/categories` (admin auth required)
- `PUT/PATCH /api/admin/categories/{category}` (admin auth required)
- `DELETE /api/admin/categories/{category}` (admin auth required)

### Favorites
- `GET /api/favorites` (auth required)
- `POST /api/favorites/{prodId}` (auth required)
- `DELETE /api/favorites/{prodId}` (auth required)

---

## Notes
- Send `Accept-Language: en` or `Accept-Language: ar` to get localized fields in responses.
- Uploaded files/images are served via Laravel's `public` storage disk.

---

## Testing

### Using Postman
1. Import `postman/ProductManagementAPI.postman_collection.json`.
2. Import `postman/Product Management API Env.postman_environment.json`.
3. Set `base_url` to `http://localhost:8000`.
4. Login as user or admin to get a token.
5. Token is saved in environment variables for subsequent requests.

---

## Common Issues & Solutions

### Issue: JWT token invalid
**Solution**: regenerate JWT secret and clear config cache.

```bash
php artisan jwt:secret --force
php artisan config:clear
```

### Issue: storage symlink broken
**Solution**: recreate symlink.

```bash
php artisan storage:link
```

### Issue: file uploads fail on PUT requests
**Solution**: for multipart requests, send `POST` with `_method=PUT`.

### Issue: category circular reference error
**Solution**: this is expected behavior. A category cannot be the parent of itself or one of its descendants.
