# 🍽️ Cafeteria Management System

A PHP-based internal cafeteria management system that allows admins to manage products, track orders, and oversee users — with a dedicated order-placement interface for employees.

---

## 📋 Features

### Admin Panel
- **Product Management** — Create, edit, delete, and toggle availability of menu items
- **Order Management** — View all orders, mark them as *Out for Delivery* or *Done*
- **User Management** — Browse registered users; edit or remove accounts
- **Financial Checks** — Filter and review total per-user spending over a date range

### Employee / User Side
- **My Orders** — View personal order history with statuses and totals
- **Cancel Orders** — Cancel an order while it is still in *Processing* status

---

## 🗂️ Project Structure

```
Cafeteria/
├── app/
│   ├── Controllers/
│   │   └── Admin/
│   │       └── ProductController.php   # Handles product CRUD + CSRF & ID validation
│   ├── Models/
│   │   └── Product.php                 # PDO-based product queries
│   ├── Services/
│   │   └── ProductService.php          # Business logic, validation, image upload
│   ├── Helpers/
│   │   ├── upload.php                  # Secure image upload helper
│   │   └── ErrorHandler.php            # Global error & exception handler
│   └── Views/
│       ├── admin/
│       │   ├── products/               # index, create, edit views
│       │   ├── orders/                 # Admin order list + my-orders
│       │   ├── users/                  # User list view
│       │   └── checks/                 # Financial checks view
│       ├── orders/
│       │   └── my-orders.php           # Employee order history
│       └── partials/
│           └── pagination.php          # Reusable pagination component
├── public/
│   ├── index.php                       # Front controller / router
│   ├── assets/
│   │   ├── js/
│   │   │   ├── orders.js
│   │   │   └── admin/checks.js
│   │   └── images/
│   │       └── default-avatar.png      # Fallback user profile picture
│   └── uploads/                        # Uploaded product images
├── storage/
│   └── logs/
│       └── error.log                   # Application error log
└── README.md
```

---

## ⚙️ Requirements

| Requirement | Version |
|-------------|---------|
| PHP | 7.4+ |
| MySQL / MariaDB | 5.7+ |
| XAMPP / Apache | Any recent |
| PDO PHP Extension | Enabled |
| Fileinfo PHP Extension | Enabled |

---

## 🚀 Installation

### 1. Clone the repository

```bash
git clone https://github.com/MahmoudAhmed184/Cafeteria.git
cd Cafeteria
```

### 2. Place in web root

Copy (or symlink) the project into your XAMPP htdocs folder:

```
/opt/lampp/htdocs/Cafeteria/
```

### 3. Create the database

Import the SQL schema into your MySQL instance:

```bash
mysql -u root -p < docs/schema.sql
```

> If no schema file exists yet, create a database named `cafeteria` and set up the tables manually (see [Database Schema](#database-schema) below).

### 4. Configure the database connection

Create `app/database.php` with your credentials:

```php
<?php

class Database
{
    private static ?PDO $instance = null;

    public static function connect(): PDO
    {
        if (self::$instance === null) {
            $dsn  = 'mysql:host=localhost;dbname=cafeteria;charset=utf8mb4';
            $user = 'root';
            $pass = '';

            self::$instance = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        }
        return self::$instance;
    }
}
```

### 5. Set permissions

```bash
chmod -R 755 public/uploads/
chmod -R 755 storage/logs/
```

### 6. Start Apache & MySQL

```bash
sudo /opt/lampp/lampp start
```

Then visit: [http://localhost/Cafeteria/public/](http://localhost/Cafeteria/public/)

---

## 🗄️ Database Schema

```sql
CREATE DATABASE IF NOT EXISTS cafeteria CHARACTER SET utf8mb4;
USE cafeteria;

CREATE TABLE categories (
    id   INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE products (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    name         VARCHAR(150)   NOT NULL,
    price        DECIMAL(10, 2) NOT NULL,
    category_id  INT            NOT NULL,
    image        VARCHAR(255)   NOT NULL,
    is_available TINYINT(1)     NOT NULL DEFAULT 1,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE users (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(150) NOT NULL,
    room_no     VARCHAR(20),
    ext         VARCHAR(20),
    profile_pic VARCHAR(255)
);

CREATE TABLE orders (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    user_id      INT            NOT NULL,
    user_name    VARCHAR(150)   NOT NULL,
    room_no      VARCHAR(20),
    ext          VARCHAR(20),
    total_amount DECIMAL(10, 2) NOT NULL,
    status       ENUM('Processing', 'Out for Delivery', 'Done', 'Cancelled') NOT NULL DEFAULT 'Processing',
    created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

---

## 🔒 Security

- **CSRF Protection** — All mutating form actions (create, update, delete, toggle) use a session-based CSRF token validated server-side.
- **Input Validation** — All `id` query parameters are validated as positive integers before use.
- **XSS Prevention** — All user-supplied data rendered in views is escaped with `htmlspecialchars()`.
- **File Upload Security** — Uploaded images are validated by MIME type (via `finfo`), size-limited to 2 MB, and stored with randomised filenames.
- **Error Logging** — Unhandled errors and exceptions are caught and written to `storage/logs/error.log`; no stack traces are exposed to users.

---

## 📁 Image Uploads

Product images are stored at:

```
public/uploads/products/<random-id>.<ext>
```

Allowed types: `image/jpeg`, `image/png`, `image/gif`, `image/webp`  
Max size: **2 MB**

---

## 📄 License

This project is open source and available under the [MIT License](LICENSE).
