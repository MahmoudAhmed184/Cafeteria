# Software Requirements Specification (SRS)

## Cafeteria Management System

| Field             | Value                                      |
| ----------------- | ------------------------------------------ |
| **Document Version** | 1.0                                     |
| **Date**             | 2026-03-14                              |
| **Project Name**     | Cafeteria Management System             |
| **Technology Stack** | PHP 8.x · MySQL 8.x · HTML/CSS/JS      |
| **Status**           | Draft                                   |

---

## Table of Contents

1. [Introduction](#1-introduction)
   1. [Purpose](#11-purpose)
   2. [Scope](#12-scope)
   3. [Definitions, Acronyms, and Abbreviations](#13-definitions-acronyms-and-abbreviations)
   4. [References](#14-references)
   5. [Overview](#15-overview)
2. [Overall Description](#2-overall-description)
   1. [Product Perspective](#21-product-perspective)
   2. [User Classes and Characteristics](#22-user-classes-and-characteristics)
   3. [Operating Environment](#23-operating-environment)
   4. [Design and Implementation Constraints](#24-design-and-implementation-constraints)
   5. [Assumptions and Dependencies](#25-assumptions-and-dependencies)
3. [System Features](#3-system-features)
   1. [Authentication and Role-Based Access](#31-authentication-and-role-based-access)
   2. [User Dashboard and Shopping Cart](#32-user-dashboard-and-shopping-cart)
   3. [Order Management](#33-order-management)
   4. [Admin — Orders Dashboard](#34-admin--orders-dashboard)
   5. [Admin — Manual Order Placement](#35-admin--manual-order-placement)
   6. [Admin — Product Management](#36-admin--product-management)
   7. [Admin — User Management](#37-admin--user-management)
   8. [Admin — Financial Checks / Reporting](#38-admin--financial-checks--reporting)
4. [External Interface Requirements](#4-external-interface-requirements)
   1. [User Interfaces](#41-user-interfaces)
   2. [Database Schema](#42-database-schema)
   3. [Entity-Relationship Description](#43-entity-relationship-description)
5. [Non-Functional Requirements](#5-non-functional-requirements)
   1. [Security](#51-security)
   2. [Usability](#52-usability)
   3. [Performance](#53-performance)
   4. [Reliability and Availability](#54-reliability-and-availability)
   5. [Maintainability](#55-maintainability)
6. [Project Directory Structure (MVC)](#6-project-directory-structure-mvc)
   1. [Directory Tree](#61-directory-tree)
   2. [Directory & File Descriptions](#62-directory--file-descriptions)
   3. [Request Lifecycle](#63-request-lifecycle)

---

## 1. Introduction

### 1.1 Purpose

This Software Requirements Specification (SRS) describes the functional and non-functional requirements for the **Cafeteria Management System** (hereafter referred to as "the System"). It is intended to serve as the **single source of truth** for the development team, quality-assurance personnel, and project stakeholders throughout the software development lifecycle.

### 1.2 Scope

The System is a web-based application that allows an organizational cafeteria to:

- Expose a digital product catalogue to employees (Users).
- Accept, track, and fulfill food and beverage orders via an online workflow.
- Provide administrators with tools to manage products, categories, users, and financial reporting.

The System **will**:

- Authenticate users and authorize actions based on predefined roles (Admin, User).
- Allow Users to browse products, manage a shopping cart, place orders, and review their order history.
- Allow Admins to manage the product catalogue, manage user accounts, process incoming orders, place manual orders on behalf of users, and generate financial check reports.

The System **will not**:

- Integrate with external payment gateways or process real payments.
- Provide a public-facing (unauthenticated) storefront.
- Include native mobile applications (it is a responsive web application only).

### 1.3 Definitions, Acronyms, and Abbreviations

| Term / Acronym | Definition |
| -------------- | ---------- |
| **SRS**        | Software Requirements Specification |
| **Admin**      | A user with the administrator role, possessing full system management privileges. |
| **User**       | An employee/customer with access to the product catalogue, shopping cart, and personal order history. |
| **EGP**        | Egyptian Pound — the currency used for all monetary values in the System. |
| **Ext.**       | Phone extension number associated with a user or delivery room. |
| **AJAX**       | Asynchronous JavaScript and XML — a technique for partial page updates without full reloads. |
| **CRUD**       | Create, Read, Update, Delete — standard data manipulation operations. |
| **bcrypt**     | A password-hashing function designed for secure credential storage. |
| **Session**    | A server-side mechanism (PHP `$_SESSION`) for persisting authentication state across HTTP requests. |

### 1.4 References

| # | Document | Description |
| - | -------- | ----------- |
| 1 | `Project Requirements.md` | Original project requirements and wireframe descriptions. |
| 2 | IEEE Std 830-1998 | IEEE Recommended Practice for Software Requirements Specifications. |

### 1.5 Overview

The remainder of this SRS is organized as follows:

- **Section 2** provides a high-level system overview, user classes, operating environment, and constraints.
- **Section 3** details every system feature with functional requirements, stimulus/response sequences, and priority levels.
- **Section 4** specifies external interface requirements including UI descriptions and the complete database schema.
- **Section 5** defines non-functional requirements covering security, usability, performance, reliability, and maintainability.

---

## 2. Overall Description

### 2.1 Product Perspective

The Cafeteria Management System is a **self-contained, server-rendered web application**. It does not replace or interface with any existing enterprise system. The System is accessed through standard web browsers and communicates with a MySQL relational database for all persistent data. AJAX (Fetch API) is used selectively to deliver a modern, interactive user experience for cart operations and order detail expansion.

### 2.2 User Classes and Characteristics

| User Class | Description | Technical Proficiency | Frequency of Use |
| ---------- | ----------- | --------------------- | ---------------- |
| **User (Employee / Customer)** | Browses the product catalogue, places orders, and reviews personal order history. Does not manage system data. | Low to moderate. The interface must be intuitive and require no training. | Daily (during working hours). |
| **Admin (Cafeteria Manager)** | Manages products, categories, and user accounts. Processes incoming orders, places manual orders for users, and generates financial reports. | Moderate. Comfortable with data-management interfaces. | Daily to weekly, depending on operational needs. |

### 2.3 Operating Environment

| Component       | Requirement |
| --------------- | ----------- |
| **Server OS**       | Linux-based server (e.g., Ubuntu 22.04+) or Windows with XAMPP/WAMP. |
| **Web Server**      | Apache 2.4+ with `mod_rewrite` enabled, or Nginx with equivalent rewrite rules. |
| **PHP Runtime**     | PHP 8.0 or later with extensions: `mysqli` or `PDO_mysql`, `gd` or `imagick`, `mbstring`, `session`. |
| **Database**        | MySQL 8.0+ or MariaDB 10.6+. |
| **Client Browser**  | Latest two major versions of Google Chrome, Mozilla Firefox, Microsoft Edge, or Safari. |
| **Network**         | The System operates within an organizational intranet or is accessible over HTTPS on the public internet. |

### 2.4 Design and Implementation Constraints

1. **Language Constraint**: All server-side logic **must** be implemented in PHP.
2. **Database Constraint**: MySQL is the sole supported RDBMS.
3. **No Framework Mandate**: The System may use plain PHP or a lightweight framework; heavy frameworks (e.g., Laravel, Symfony) are not mandated but are not prohibited.
4. **Currency**: All monetary values are denominated in **EGP (Egyptian Pound)** and stored as `DECIMAL(10,2)`.
5. **File Uploads**: Product images and user profile pictures must be stored on the server filesystem. Uploaded files must not exceed a configurable maximum size (default: 2 MB) and must be restricted to safe image formats (JPEG, PNG, GIF, WebP).
6. **Session-Based Auth**: Authentication must rely on server-side PHP sessions; token-based authentication (JWT) is out of scope.

### 2.5 Assumptions and Dependencies

| # | Assumption / Dependency |
| - | ----------------------- |
| 1 | A pre-configured web server with PHP and MySQL is available in the deployment environment. |
| 2 | An initial Admin account will be seeded into the database during installation. |
| 3 | All users belong to the same organization and have been assigned rooms and phone extensions. |
| 4 | Network connectivity between client browsers and the server is reliable during operating hours. |
| 5 | The Rooms table will be pre-populated by an administrator before the System goes live. |

---

## 3. System Features

Each feature is documented with: **Description**, **Priority**, **Stimulus / Response Sequences**, and numbered **Functional Requirements**.

---

### 3.1 Authentication and Role-Based Access

**Priority:** High

#### 3.1.1 Description

The System shall provide a secure login mechanism that authenticates users by email and password and redirects them to the appropriate dashboard based on their assigned role.

#### 3.1.2 Stimulus / Response Sequences

| # | Stimulus | Response |
| - | -------- | -------- |
| 1 | User navigates to the application URL without an active session. | System displays the Login page. |
| 2 | User submits valid Admin credentials. | System creates a session and redirects to the Admin Dashboard. |
| 3 | User submits valid User credentials. | System creates a session and redirects to the User Dashboard. |
| 4 | User submits invalid credentials. | System displays an error message; no session is created. |
| 5 | User clicks "Forget Password?". | System initiates the password-recovery flow. |
| 6 | Authenticated user accesses a page outside their role. | System returns HTTP 403 Forbidden or redirects to their own dashboard. |

#### 3.1.3 Functional Requirements

| ID       | Requirement |
| -------- | ----------- |
| **FR-AUTH-001** | The System shall present a Login form requesting **Email** and **Password**. |
| **FR-AUTH-002** | The System shall authenticate the user by comparing the submitted password against the stored bcrypt hash using `password_verify()`. |
| **FR-AUTH-003** | Upon successful authentication, the System shall store the user's `id`, `name`, `role_id`, and `profile_pic` in the server-side PHP `$_SESSION`. |
| **FR-AUTH-004** | The System shall redirect Admin users (identified by `role_id`) to the Admin Orders Dashboard and regular Users to the User Dashboard. |
| **FR-AUTH-005** | The System shall provide a "Forget Password?" link on the Login page that initiates a password-reset workflow (e.g., email-based token reset or security-question verification). |
| **FR-AUTH-006** | The System shall enforce role-based access control: Users shall not access Admin-only pages, and Admins shall not be restricted from Admin pages. |
| **FR-AUTH-007** | The System shall provide a **Logout** action that destroys the session and redirects to the Login page. |
| **FR-AUTH-008** | The System shall regenerate the session ID upon login (`session_regenerate_id(true)`) to prevent session fixation attacks. |

---

### 3.2 User Dashboard and Shopping Cart

**Priority:** High

#### 3.2.1 Description

The User Dashboard is the primary interface for authenticated Users. It presents the product catalogue, a real-time shopping cart driven by AJAX, and the ability to place orders with room selection and special instructions.

#### 3.2.2 Stimulus / Response Sequences

| # | Stimulus | Response |
| - | -------- | -------- |
| 1 | User loads the Dashboard. | System displays available products (image, name, price) and an empty cart. |
| 2 | User types into the search bar. | System filters the displayed products in real-time by name. |
| 3 | User clicks "Add to Cart" on a product. | System adds the product to the cart (AJAX); cart widget updates without page reload. |
| 4 | User clicks the **+** or **−** button next to a cart item. | System increments / decrements the item quantity (AJAX); totals recalculate. |
| 5 | User clicks the **X** button next to a cart item. | System removes the item from the cart (AJAX). |
| 6 | User selects a room, optionally adds notes, clicks "Confirm". | System validates the cart, creates the order, and displays a success message. |

#### 3.2.3 Functional Requirements

| ID       | Requirement |
| -------- | ----------- |
| **FR-CART-001** | The System shall display all products where `is_available = TRUE`, showing the product **image**, **name**, and **price** (in EGP). |
| **FR-CART-002** | The System shall provide a **search bar** that filters the product catalogue by product name. Filtering should occur client-side or via AJAX as the user types. |
| **FR-CART-003** | The System shall allow the user to **add a product** to the shopping cart. If the product is already in the cart, the quantity shall be incremented by one. |
| **FR-CART-004** | The System shall allow the user to **increase (+)** or **decrease (−)** the quantity of each cart item via AJAX. Decreasing below 1 shall remove the item. |
| **FR-CART-005** | The System shall allow the user to **remove (X)** any item from the cart via AJAX. |
| **FR-CART-006** | The System shall display the **individual line total** (unit price × quantity) for each cart item and the **grand total** of all items. |
| **FR-CART-007** | The System shall provide a **Room ComboBox** dropdown populated from the `rooms` table for delivery location selection. |
| **FR-CART-008** | The System shall provide a **Notes** text area for special instructions (max 500 characters). |
| **FR-CART-009** | Upon clicking **Confirm**, the System shall validate that: (a) the cart is non-empty, (b) a room has been selected. If validation fails, an error message is displayed. |
| **FR-CART-010** | Upon successful validation, the System shall create a record in the `orders` table (status = `Processing`) and corresponding records in the `order_items` table, capturing the price at the time of order (`price_at_time_of_order`). |
| **FR-CART-011** | The System shall display a **Latest Order Widget** showing the user's most recent order summary (items, total, status) for quick reference. |
| **FR-CART-012** | Cart state shall be managed **server-side** (via `$_SESSION` or a temporary database table) to persist across page reloads before order submission. |

---

### 3.3 Order Management (User – My Orders)

**Priority:** High

#### 3.3.1 Description

Users can view their complete order history, filter by date range, expand orders to view item-level details, and cancel orders that have not yet left the processing stage.

#### 3.3.2 Stimulus / Response Sequences

| # | Stimulus | Response |
| - | -------- | -------- |
| 1 | User navigates to "My Orders". | System displays a paginated table of the user's orders (Date, Status, Amount, Action). |
| 2 | User applies date-range filters. | System re-queries and displays only orders within the specified range. |
| 3 | User clicks the **+** expansion icon on an order row. | System fetches (AJAX) and displays the item breakdown (product name, quantity, line total). |
| 4 | User clicks **CANCEL** on a "Processing" order. | System prompts for confirmation; upon confirmation, sets the order status to `Cancelled`. |
| 5 | User clicks **CANCEL** on a non-"Processing" order. | Button is disabled or hidden; no action is available. |

#### 3.3.3 Functional Requirements

| ID       | Requirement |
| -------- | ----------- |
| **FR-ORD-001** | The System shall display a table of the authenticated user's orders with columns: **Order Date**, **Status**, **Amount (EGP)**, and **Action**. |
| **FR-ORD-002** | The System shall support the following order statuses: `Processing`, `Out for Delivery`, `Done`, and `Cancelled`. |
| **FR-ORD-003** | The System shall display a **CANCEL** button for orders with status `Processing` only. |
| **FR-ORD-004** | **Order Cancellation Business Logic**: When a user initiates cancellation: (1) the System shall verify on the server side that the order's current status is `Processing`; (2) if verified, the status shall be updated to `Cancelled`; (3) if the status has already changed (race condition), the System shall return an error message stating the order can no longer be cancelled. This prevents cancellation of orders that have already been dispatched. |
| **FR-ORD-005** | The System shall provide a **+** icon on each order row. Clicking it shall fetch (via AJAX) the order's items and display them in an expanded sub-row showing **Product Name**, **Quantity**, and **Line Total**. |
| **FR-ORD-006** | The System shall provide **Date From** and **Date To** filter inputs to restrict the displayed orders to a specific date range. |
| **FR-ORD-007** | The System shall implement **pagination** controls (Previous / Page Number / Next) with a configurable page size (default: 10 orders per page). |

---

### 3.4 Admin — Orders Dashboard

**Priority:** High

#### 3.4.1 Description

The Admin Orders Dashboard presents all incoming orders to the cafeteria administrator for processing and dispatch.

#### 3.4.2 Stimulus / Response Sequences

| # | Stimulus | Response |
| - | -------- | -------- |
| 1 | Admin navigates to Orders Dashboard. | System displays all orders with status `Processing`, showing Date, User Name, Room, Ext., Amount. |
| 2 | Admin clicks the **Deliver** button on an order. | System updates order status from `Processing` to `Out for Delivery`. |
| 3 | Admin clicks the expansion icon on an order. | System displays the item/quantity breakdown for that order. |

#### 3.4.3 Functional Requirements

| ID       | Requirement |
| -------- | ----------- |
| **FR-ADM-ORD-001** | The System shall display a table of all incoming orders with columns: **Order Date**, **User Name**, **Room**, **Ext.**, **Amount (EGP)**, and **Action**. |
| **FR-ADM-ORD-002** | The System shall provide a **Deliver** action button that transitions an order's status from `Processing` to `Out for Delivery`. |
| **FR-ADM-ORD-003** | The System shall support marking an order as `Done` once delivery is confirmed. |
| **FR-ADM-ORD-004** | The System shall provide an expandable row view (AJAX) showing the exact product names, quantities, and line totals for each order. |

---

### 3.5 Admin — Manual Order Placement

**Priority:** Medium

#### 3.5.1 Description

Administrators can place orders **on behalf of** any registered user. The interface mirrors the User Dashboard but includes a user-selection mechanism.

#### 3.5.2 Functional Requirements

| ID       | Requirement |
| -------- | ----------- |
| **FR-ADM-MAN-001** | The System shall provide a searchable **"Add to User"** dropdown/combobox populated with all registered users. |
| **FR-ADM-MAN-002** | The selected user's identity shall be used as the `user_id` when the order is created (not the Admin's own ID). |
| **FR-ADM-MAN-003** | All standard cart features (product search, add/remove items, quantity adjustment, room selection, notes, confirm) shall be available on this page, identical to [Section 3.2](#32-user-dashboard-and-shopping-cart). |

---

### 3.6 Admin — Product Management

**Priority:** High

#### 3.6.1 Description

Admins can view, add, edit, and delete products and their categories.

#### 3.6.2 Functional Requirements

| ID       | Requirement |
| -------- | ----------- |
| **FR-ADM-PRD-001** | The System shall display a table of all products with columns: **Product Name**, **Price (EGP)**, **Image (Thumbnail)**, **Availability Status**, and **Action** (Edit / Delete). |
| **FR-ADM-PRD-002** | The System shall allow toggling a product's **availability** (`is_available`) between available and unavailable. Unavailable products shall not appear in the User catalogue. |
| **FR-ADM-PRD-003** | The System shall provide an **Add Product** form with the following fields: Product Name (required), Price in EGP (required, numeric > 0), Category (required, dropdown from `categories` table), and Product Image (file upload, required). |
| **FR-ADM-PRD-004** | The System shall provide an **"Add Category"** action (link/button or inline form) allowing the Admin to create new product categories dynamically without leaving the page. |
| **FR-ADM-PRD-005** | The **Edit Product** form shall pre-populate all fields with existing values and allow modification. Changing the image is optional (the existing image is retained if no new file is uploaded). |
| **FR-ADM-PRD-006** | **Delete Product** shall either soft-delete (set a flag) or prevent deletion of products referenced in existing `order_items` records. If no order references exist, the product and its associated image file may be permanently removed. |
| **FR-ADM-PRD-007** | The form shall include **Save** and **Reset** buttons. Reset clears the form to default/empty values. |

---

### 3.7 Admin — User Management

**Priority:** High

#### 3.7.1 Description

Admins can view, add, edit, and delete user accounts.

#### 3.7.2 Functional Requirements

| ID       | Requirement |
| -------- | ----------- |
| **FR-ADM-USR-001** | The System shall display a table of all users with columns: **Name**, **Room**, **Profile Image (Thumbnail)**, **Ext.**, and **Action** (Edit / Delete). |
| **FR-ADM-USR-002** | The System shall provide an **Add User** form with the following fields: Name (required), Email (required, unique, valid format), Password (required, min 8 characters), Confirm Password (required, must match Password), Room No. (required, dropdown from `rooms` table), Ext. (required), and Profile Picture (file upload, optional). |
| **FR-ADM-USR-003** | The System shall hash the password using `password_hash()` with `PASSWORD_BCRYPT` before storing it in the database. |
| **FR-ADM-USR-004** | The **Edit User** form shall pre-populate all non-sensitive fields. Password fields shall be blank; leaving them blank shall retain the existing password hash. |
| **FR-ADM-USR-005** | **Delete User** shall be restricted: users with existing orders shall be soft-deleted (deactivated) rather than permanently removed, to preserve referential integrity and financial records. |
| **FR-ADM-USR-006** | The form shall include **Save** and **Reset** buttons. |

---

### 3.8 Admin — Financial Checks / Reporting

**Priority:** High

#### 3.8.1 Description

The Checks module provides financial reporting capabilities, allowing administrators to audit user spending over configurable date ranges with drill-down capability from user-level summaries to individual order items.

#### 3.8.2 Stimulus / Response Sequences

| # | Stimulus | Response |
| - | -------- | -------- |
| 1 | Admin navigates to the Checks page. | System displays filter controls (Date From, Date To, User dropdown) and an empty results area. |
| 2 | Admin selects filter criteria and submits. | System queries for orders matching the criteria and displays a summary table: User Name → Total Amount. |
| 3 | Admin clicks on a user row. | System expands to show that user's individual orders (Order Date, Amount). |
| 4 | Admin clicks on a specific order row. | System expands further to show the exact items and quantities of that order. |

#### 3.8.3 Financial Checks Business Logic

The Financial Checks feature implements a **three-level drill-down** reporting model:

1. **Level 1 — User Summary**: Aggregates the `total_amount` of all non-cancelled orders for each user within the specified date range. The query includes only orders with statuses `Processing`, `Out for Delivery`, or `Done`. `Cancelled` orders are **excluded** from financial totals.
2. **Level 2 — User Order List**: Upon selecting a user, the System displays all individual orders placed by that user within the date range, showing Order Date and Amount.
3. **Level 3 — Order Item Breakdown**: Upon selecting a specific order, the System displays the product-level detail: Product Name, Quantity, and Price at Time of Order.

If the **User** dropdown filter is set to a specific user, only that user's data is shown at Level 1. If set to "All Users", every user with at least one qualifying order is displayed.

#### 3.8.4 Functional Requirements

| ID       | Requirement |
| -------- | ----------- |
| **FR-ADM-CHK-001** | The System shall provide **Date From** and **Date To** filter inputs for defining the reporting period. |
| **FR-ADM-CHK-002** | The System shall provide a **User** dropdown filter (populated from the users table) allowing selection of a specific user or all users. |
| **FR-ADM-CHK-003** | The System shall display a summary table listing each user and their **Total Amount (EGP)** spent during the reporting period, excluding `Cancelled` orders. |
| **FR-ADM-CHK-004** | Clicking a user row shall expand (via AJAX or inline toggle) to show their individual orders within the date range, with columns: **Order Date** and **Amount (EGP)**. |
| **FR-ADM-CHK-005** | Clicking a specific order row shall further expand to show the item-level breakdown: **Product Name**, **Quantity**, and **Price at Time of Order (EGP)**. |
| **FR-ADM-CHK-006** | Financial totals shall be computed from the `total_amount` field of the `orders` table and shall include only orders with statuses `Processing`, `Out for Delivery`, or `Done`. |

---

## 4. External Interface Requirements

### 4.1 User Interfaces

The System comprises the following distinct user-facing pages:

| # | Page | Accessible By | Key UI Elements |
| - | ---- | ------------- | --------------- |
| 1 | **Login** | Unauthenticated | Email input, Password input, "Login" button, "Forget Password?" link. |
| 2 | **User Dashboard / Home** | User | Product grid/list, search bar, shopping cart sidebar/widget, room dropdown, notes textarea, confirm button, latest order widget. |
| 3 | **My Orders** | User | Orders table, date-range filters, pagination, expandable rows, cancel button. |
| 4 | **Admin Orders Dashboard** | Admin | Incoming orders table, deliver button, expandable item rows. |
| 5 | **Manual Order** | Admin | User-selection combobox, product catalogue, cart (identical to User Dashboard). |
| 6 | **Products List** | Admin | Products table with availability toggle, edit/delete actions. |
| 7 | **Add/Edit Product** | Admin | Form: name, price, category dropdown, image upload, add-category link, save/reset buttons. |
| 8 | **Users List** | Admin | Users table with edit/delete actions. |
| 9 | **Add/Edit User** | Admin | Form: name, email, password, confirm password, room dropdown, ext, profile picture upload, save/reset buttons. |
| 10 | **Checks / Reporting** | Admin | Date-range filters, user dropdown, summary table, expandable order and item rows. |

**General UI Principles:**

- All interactive cart operations (add, remove, quantity change) and order-row expansions shall use **AJAX/Fetch** for partial page updates.
- Forms shall provide clear **client-side validation** feedback before server submission.
- The interface shall be **responsive** and usable on desktop and tablet screen sizes.

---

### 4.2 Database Schema

The following tables define the System's relational data model.

#### 4.2.1 `users`

| Column | Data Type | Constraints | Description |
| ------ | --------- | ----------- | ----------- |
| `id` | `BIGINT UNSIGNED` | `PRIMARY KEY`, `AUTO_INCREMENT` | Unique user identifier. |
| `name` | `VARCHAR(100)` | `NOT NULL` | Full name of the user. |
| `email` | `VARCHAR(150)` | `NOT NULL`, `UNIQUE` | Email address used for login. |
| `password` | `VARCHAR(255)` | `NOT NULL` | bcrypt-hashed password. |
| `room_no` | `VARCHAR(20)` | `NOT NULL` | Room number for delivery. |
| `ext` | `VARCHAR(20)` | `NOT NULL` | Phone extension number. |
| `profile_pic` | `VARCHAR(255)` | `NULLABLE` | File path to the profile image. |
| `role_id` | `TINYINT UNSIGNED` | `NOT NULL`, `DEFAULT 2` | Role identifier: `1` = Admin, `2` = User. |
| `is_active` | `TINYINT(1)` | `NOT NULL`, `DEFAULT 1` | Soft-delete flag: `1` = active, `0` = deactivated. |
| `created_at` | `TIMESTAMP` | `DEFAULT CURRENT_TIMESTAMP` | Account creation timestamp. |

#### 4.2.2 `categories`

| Column | Data Type | Constraints | Description |
| ------ | --------- | ----------- | ----------- |
| `id` | `INT UNSIGNED` | `PRIMARY KEY`, `AUTO_INCREMENT` | Unique category identifier. |
| `name` | `VARCHAR(100)` | `NOT NULL`, `UNIQUE` | Category name (e.g., "Beverages", "Sandwiches"). |

#### 4.2.3 `products`

| Column | Data Type | Constraints | Description |
| ------ | --------- | ----------- | ----------- |
| `id` | `BIGINT UNSIGNED` | `PRIMARY KEY`, `AUTO_INCREMENT` | Unique product identifier. |
| `name` | `VARCHAR(150)` | `NOT NULL` | Product display name. |
| `price` | `DECIMAL(10,2)` | `NOT NULL` | Price in EGP. |
| `image` | `VARCHAR(255)` | `NOT NULL` | File path to the product image. |
| `category_id` | `INT UNSIGNED` | `NOT NULL`, `FOREIGN KEY → categories(id)` | Associated category. |
| `is_available` | `TINYINT(1)` | `NOT NULL`, `DEFAULT 1` | Availability flag: `1` = available, `0` = unavailable. |

#### 4.2.4 `rooms`

| Column | Data Type | Constraints | Description |
| ------ | --------- | ----------- | ----------- |
| `id` | `INT UNSIGNED` | `PRIMARY KEY`, `AUTO_INCREMENT` | Unique room identifier. |
| `room_number` | `VARCHAR(20)` | `NOT NULL`, `UNIQUE` | Room number/label. |

#### 4.2.5 `orders`

| Column | Data Type | Constraints | Description |
| ------ | --------- | ----------- | ----------- |
| `id` | `BIGINT UNSIGNED` | `PRIMARY KEY`, `AUTO_INCREMENT` | Unique order identifier. |
| `user_id` | `BIGINT UNSIGNED` | `NOT NULL`, `FOREIGN KEY → users(id)` | The user who placed (or for whom) the order was placed. |
| `room_no` | `VARCHAR(20)` | `NOT NULL` | Delivery room at the time of order. |
| `notes` | `TEXT` | `NULLABLE` | Special instructions from the user (max 500 characters enforced at application level). |
| `total_amount` | `DECIMAL(10,2)` | `NOT NULL` | Sum of all line totals in the order. |
| `status` | `ENUM('Processing','Out for Delivery','Done','Cancelled')` | `NOT NULL`, `DEFAULT 'Processing'` | Current order status. |
| `created_at` | `TIMESTAMP` | `DEFAULT CURRENT_TIMESTAMP` | Order placement timestamp. |

#### 4.2.6 `order_items`

| Column | Data Type | Constraints | Description |
| ------ | --------- | ----------- | ----------- |
| `id` | `BIGINT UNSIGNED` | `PRIMARY KEY`, `AUTO_INCREMENT` | Unique line-item identifier. |
| `order_id` | `BIGINT UNSIGNED` | `NOT NULL`, `FOREIGN KEY → orders(id) ON DELETE CASCADE` | Parent order. |
| `product_id` | `BIGINT UNSIGNED` | `NOT NULL`, `FOREIGN KEY → products(id)` | The product ordered. |
| `quantity` | `INT UNSIGNED` | `NOT NULL`, `CHECK (quantity > 0)` | Number of units ordered. |
| `price_at_time_of_order` | `DECIMAL(10,2)` | `NOT NULL` | Unit price captured at order time, ensuring historical accuracy even if product prices change. |

---

### 4.3 Entity-Relationship Description

The following describes the relationships between entities in the database:

```
┌────────────────┐         ┌────────────────┐
│    categories   │         │      rooms      │
│────────────────│         │────────────────│
│ id (PK)        │         │ id (PK)        │
│ name           │         │ room_number    │
└───────┬────────┘         └────────────────┘
        │ 1
        │
        │ N
┌───────┴────────┐         ┌────────────────┐
│    products     │         │     users       │
│────────────────│         │────────────────│
│ id (PK)        │         │ id (PK)        │
│ name           │         │ name           │
│ price          │         │ email          │
│ image          │         │ password       │
│ category_id(FK)│         │ room_no        │
│ is_available   │         │ ext            │
└───────┬────────┘         │ profile_pic    │
        │                  │ role_id        │
        │                  │ is_active      │
        │                  └───────┬────────┘
        │                          │
        │ N                        │ 1
        │                          │
┌───────┴────────┐                 │ N
│  order_items    │         ┌──────┴─────────┐
│────────────────│         │     orders      │
│ id (PK)        │         │────────────────│
│ order_id (FK)  ├────N:1──┤ id (PK)        │
│ product_id(FK) │         │ user_id (FK)   │
│ quantity       │         │ room_no        │
│ price_at_time  │         │ notes          │
│ _of_order      │         │ total_amount   │
└────────────────┘         │ status         │
                           │ created_at     │
                           └────────────────┘
```

**Relationship Summary:**

| Relationship | Cardinality | Description |
| ------------ | ----------- | ----------- |
| `categories` → `products` | 1 : N | One category contains many products. |
| `users` → `orders` | 1 : N | One user places many orders. |
| `orders` → `order_items` | 1 : N | One order contains many line items. Items cascade-delete with the order. |
| `products` → `order_items` | 1 : N | One product can appear in many order line items. |
| `rooms` → (referenced by `orders.room_no` and `users.room_no`) | Reference | Rooms provide valid values for delivery locations. |

---

## 5. Non-Functional Requirements

### 5.1 Security

| ID | Requirement |
| -- | ----------- |
| **NFR-SEC-001** | All user passwords **must** be hashed using `password_hash()` with the `PASSWORD_BCRYPT` algorithm before database storage. Plain-text passwords shall never be stored or logged. |
| **NFR-SEC-002** | Password verification shall use `password_verify()` to compare user input against the stored hash. Direct string comparison is prohibited. |
| **NFR-SEC-003** | PHP sessions shall be configured with the following secure settings: `session.cookie_httponly = 1` (prevent JavaScript access to session cookies), `session.cookie_secure = 1` (HTTPS only, when deployed over TLS), `session.use_strict_mode = 1` (reject uninitialized session IDs). |
| **NFR-SEC-004** | The session ID shall be regenerated upon login (`session_regenerate_id(true)`) to mitigate session fixation attacks. |
| **NFR-SEC-005** | All user-supplied input shall be sanitized and validated server-side. Database queries shall use **prepared statements** with parameterized queries (via `PDO` or `mysqli`) to prevent SQL Injection. |
| **NFR-SEC-006** | All output rendered in HTML shall be escaped using `htmlspecialchars()` with `ENT_QUOTES` to prevent Cross-Site Scripting (XSS). |
| **NFR-SEC-007** | **File Upload Validation**: Uploaded images shall be validated by: (1) checking the MIME type via `finfo_file()` (not relying on the file extension alone); (2) restricting acceptable types to `image/jpeg`, `image/png`, `image/gif`, `image/webp`; (3) enforcing a maximum file size (configurable, default 2 MB); (4) renaming the file to a unique, non-guessable name (e.g., UUID or hash-based) before saving to the upload directory. |
| **NFR-SEC-008** | The upload directory shall be configured to **disallow script execution** (e.g., via `.htaccess` rules: `php_flag engine off`). |
| **NFR-SEC-009** | CSRF protection shall be implemented for all state-changing forms and AJAX requests using synchronized token patterns. |
| **NFR-SEC-010** | Admin-only endpoints shall validate the user's role on every request, not just at the UI level. |

### 5.2 Usability

| ID | Requirement |
| -- | ----------- |
| **NFR-USE-001** | The System shall be usable by employees with **no prior training**. Navigation, labels, and workflows shall be self-explanatory. |
| **NFR-USE-002** | Error messages shall be **descriptive and actionable** (e.g., "Email is already registered" rather than "Error 1062"). |
| **NFR-USE-003** | Form submissions shall provide **visual feedback** (loading spinners, success/error toasts) within 1 second. |
| **NFR-USE-004** | The interface shall be **responsive** and functional on screens with a minimum width of 768px (tablet landscape). |
| **NFR-USE-005** | All interactive elements (buttons, links, inputs) shall have clearly visible **focus and hover states** for accessibility. |

### 5.3 Performance

| ID | Requirement |
| -- | ----------- |
| **NFR-PERF-001** | Page load time for any page shall not exceed **3 seconds** under normal operating conditions (≤ 50 concurrent users). |
| **NFR-PERF-002** | AJAX cart operations (add, remove, quantity change) shall complete and reflect in the UI within **500 milliseconds**. |
| **NFR-PERF-003** | Database queries for reporting (Financial Checks) shall execute within **2 seconds** for datasets of up to 10,000 orders. Appropriate indexes shall be created on `orders.user_id`, `orders.created_at`, and `orders.status`. |
| **NFR-PERF-004** | Product images shall be stored in an optimized format and served at appropriate resolutions (thumbnails for lists, full-size for detail views) to minimize bandwidth. |

### 5.4 Reliability and Availability

| ID | Requirement |
| -- | ----------- |
| **NFR-REL-001** | The System shall be available during organizational working hours (e.g., 08:00–18:00, local time) with a minimum uptime target of **99%** during those hours. |
| **NFR-REL-002** | Order placement shall be an **atomic transaction**: if any step fails (e.g., inserting an order item), the entire order shall be rolled back using MySQL transactions (`BEGIN`, `COMMIT`, `ROLLBACK`). All transactional logic (order creation, status transitions, cancellation) **must** be encapsulated within the **Service layer** (`app/Services/`) and executed via PDO transactions to guarantee data consistency. |
| **NFR-REL-003** | The System shall log all server-side errors to a log file (not displayed to end users) for debugging purposes. |

### 5.5 Maintainability

| ID | Requirement |
| -- | ----------- |
| **NFR-MNT-001** | The codebase shall follow the MVC directory structure defined in [Section 6](#6-project-directory-structure-mvc), separating concerns into Models, Views, Controllers, middleware, helpers, and public assets. |
| **NFR-MNT-002** | Database connection parameters shall be stored in a **single configuration file** (e.g., `config/db.php`) and not hard-coded across scripts. |
| **NFR-MNT-003** | Reusable UI components (header, footer, sidebar, navigation) shall be implemented as **PHP includes** to prevent duplication. |
| **NFR-MNT-004** | The database schema shall be versioned. An SQL migration script shall be provided for initial setup and future schema changes. |

---

## 6. Project Directory Structure (MVC)

The project follows a **Model-View-Controller-Service (MVC-S)** architecture implemented in vanilla PHP. A single **front-controller** (`public/index.php`) receives all HTTP requests, delegates routing to the appropriate Controller. Controllers delegate business logic to **Services**, which interact with Models for data access. Controllers then render Views with the resulting data.

### 6.1 Directory Tree

```
cafeteria-management-system/
│
├── public/                          # ← Web server document root
│   ├── index.php                    # Front controller — all requests enter here
│   ├── .htaccess                    # Apache rewrite rules (route everything to index.php)
│   │
│   └── assets/                      # Static assets (publicly accessible)
│       ├── css/
│       │   ├── style.css             # Global stylesheet
│       │   ├── auth.css              # Login / password-reset pages
│       │   ├── dashboard.css         # User dashboard & cart
│       │   ├── admin.css             # Admin panel pages
│       │   └── components.css        # Reusable UI components (modals, toasts, tables)
│       ├── js/
│       │   ├── app.js                # Global utilities (AJAX helpers, CSRF token injection)
│       │   ├── cart.js               # Shopping cart AJAX logic (+/−/X, totals)
│       │   ├── orders.js             # Order expansion & cancellation AJAX
│       │   ├── search.js             # Product search / filter
│       │   └── admin/
│       │       ├── products.js       # Add-category modal, image preview
│       │       ├── users.js          # User form validation
│       │       └── checks.js         # Financial report drill-down
│       └── images/
│           ├── logo.png              # Site logo
│           └── default-avatar.png    # Fallback profile picture
│
├── app/                             # Application core (MVC)
│   │
│   ├── Controllers/                 # C — Controllers (handle requests, delegate to services, render views)
│   │   ├── AuthController.php       # Login, logout, forget-password
│   │   ├── DashboardController.php  # User home / product catalogue
│   │   ├── CartController.php       # AJAX cart operations (add, update, remove, confirm)
│   │   ├── OrderController.php      # User order history, cancellation, item expansion
│   │   ├── Admin/
│   │   │   ├── AdminOrderController.php   # Incoming orders, deliver/done actions
│   │   │   ├── ManualOrderController.php  # Place orders on behalf of users
│   │   │   ├── ProductController.php      # CRUD products & categories
│   │   │   ├── UserController.php         # CRUD user accounts
│   │   │   └── CheckController.php        # Financial checks / reporting
│   │
│   ├── Services/                    # S — Services (encapsulate business logic & transactions)
│   │   ├── AuthService.php          # Credential verification, session management
│   │   ├── CartService.php          # Cart validation, price snapshot, session cart ops
│   │   ├── OrderService.php         # Order creation (atomic txn), status transitions, cancellation
│   │   ├── ProductService.php       # Product CRUD rules, availability toggling, image handling
│   │   ├── UserService.php          # User CRUD rules, soft-delete logic, password hashing
│   │   └── CheckService.php         # Financial aggregation queries, drill-down data assembly
│   │
│   ├── Models/                      # M — Models (database queries via PDO — data access only)
│   │   ├── User.php                 # Users table queries
│   │   ├── Product.php              # Products table queries, availability toggle
│   │   ├── Category.php             # Categories table queries
│   │   ├── Order.php                # Orders table queries, status transitions
│   │   ├── OrderItem.php            # Order_items table queries
│   │   └── Room.php                 # Rooms table queries
│   │
│   ├── Views/                       # V — Views (PHP/HTML templates)
│   │   ├── layouts/
│   │   │   ├── app.php              # Main HTML shell (head, nav, footer, content yield)
│   │   │   ├── auth.php             # Minimal layout for login / password-reset
│   │   │
│   │   ├── partials/                # Reusable UI fragments (included via PHP)
│   │   │   ├── header.php           # Top navigation bar
│   │   │   ├── sidebar.php          # Admin sidebar navigation
│   │   │   ├── footer.php           # Site footer
│   │   │   ├── cart_widget.php       # Shopping cart sidebar component
│   │   │   ├── latest_order.php      # Latest-order quick-view widget
│   │   │   ├── pagination.php        # Reusable pagination controls
│   │   │   └── toast.php             # Success / error toast notification
│   │   │
│   │   ├── auth/
│   │   │   ├── login.php             # Login form
│   │   │   └── forget_password.php   # Password recovery form
│   │   │
│   │   ├── user/                     # User-panel views
│   │   │   ├── dashboard.php         # Product catalogue + cart
│   │   │   └── orders.php            # My Orders page
│   │   │
│   │   └── admin/                    # Admin-panel views
│   │       ├── orders.php            # Incoming orders dashboard
│   │       ├── manual_order.php      # Manual order form
│   │       ├── products/
│   │       │   ├── index.php         # Products list table
│   │       │   └── form.php          # Add / edit product form
│   │       ├── users/
│   │       │   ├── index.php         # Users list table
│   │       │   └── form.php          # Add / edit user form
│   │       └── checks.php            # Financial checks / reporting
│   │
│   └── Router.php                    # URL-to-controller dispatcher
│
├── config/                           # Configuration files
│   ├── app.php                       # App-wide constants (APP_NAME, BASE_URL, UPLOAD_MAX_SIZE)
│   ├── database.php                  # DB host, name, user, password (single source of truth)
│   └── routes.php                    # Route definitions mapping URI patterns → Controller@method
│
├── helpers/                          # Global helper functions
│   ├── functions.php                 # redirect(), asset(), old(), e() (htmlspecialchars wrapper)
│   ├── validation.php                # validate_required(), validate_email(), validate_file_upload()
│   └── csrf.php                      # generate_csrf_token(), verify_csrf_token()
│
├── middleware/                        # Request middleware (auth guards)
│   ├── AuthMiddleware.php             # Redirects unauthenticated users to login
│   ├── AdminMiddleware.php            # Restricts access to admin-only routes
│   └── GuestMiddleware.php            # Prevents logged-in users from accessing login page
│
├── database/                          # Database setup & versioning
│   ├── migrations/
│   │   ├── 001_create_users_table.sql
│   │   ├── 002_create_categories_table.sql
│   │   ├── 003_create_products_table.sql
│   │   ├── 004_create_rooms_table.sql
│   │   ├── 005_create_orders_table.sql
│   │   └── 006_create_order_items_table.sql
│   ├── seeders/
│   │   ├── admin_seeder.sql           # Initial admin account
│   │   └── rooms_seeder.sql           # Pre-populated room list
│   └── schema.sql                     # Full combined schema (all tables)
│
├── uploads/                           # User-uploaded files (outside public/ for security)
│   ├── products/                      # Product images
│   └── profiles/                      # User profile pictures
│
├── logs/                              # Application log files
│   └── error.log                      # Server-side error log (NFR-REL-003)
│
├── .htaccess                          # Root-level rewrite to public/ (optional, server-dependent)
├── .gitignore                         # Ignore uploads/, logs/, config/database.php (credentials)
└── README.md                          # Project setup and deployment instructions
```

### 6.2 Directory & File Descriptions

| Directory / File | Purpose |
| ---------------- | ------- |
| **`public/`** | The **only directory exposed** by the web server (`DocumentRoot`). Contains the front controller, `.htaccess` rewrite rules, and all static assets (CSS, JS, images). No PHP application logic resides here other than `index.php`. |
| **`public/index.php`** | **Front controller.** Bootstraps the application: loads config, starts the session, autoloads classes, initializes the `Router`, and dispatches the request to the appropriate Controller method. |
| **`public/.htaccess`** | Apache rewrite rule: `RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]`. Routes all non-file, non-directory requests to `index.php`. |
| **`public/assets/`** | Static resources organized by type (`css/`, `js/`, `images/`). JS files are split per feature for maintainability; admin-specific scripts are namespaced under `js/admin/`. |
| **`app/Controllers/`** | Each controller class handles one feature area. Methods typically: (1) apply middleware, (2) call Service methods, (3) pass data to a View. Admin controllers are grouped in the `Admin/` subdirectory. Controllers must **not** contain business logic directly; they act as thin HTTP adapters. |
| **`app/Services/`** | One service per domain area. Each service class encapsulates business rules, validation logic, and transactional workflows (e.g., `OrderService` wraps order creation in a PDO transaction). Services call Models for data access and are the **single location** for complex operations like order state transitions, cancellation guards, and financial aggregation. |
| **`app/Models/`** | One model per database table. Each model class encapsulates all SQL queries (via PDO prepared statements) for its entity. Models are **pure data-access objects** — they do not contain business rules, echo HTML, or handle HTTP. |
| **`app/Views/`** | PHP template files that receive data arrays from controllers and render HTML. **Layouts** provide the outer HTML shell; **partials** are reusable includes (header, footer, sidebar, pagination). Feature views are grouped by role (`user/`, `admin/`). |
| **`app/Views/layouts/`** | Wrapper templates. `app.php` is the main layout (includes `<head>`, nav, footer, content area). `auth.php` is a minimal layout for unauthenticated pages. |
| **`app/Views/partials/`** | Reusable view fragments included via `require` or `include`. Eliminates duplication per **NFR-MNT-003**. |
| **`app/Router.php`** | Maps incoming URL patterns (from `config/routes.php`) to `Controller@method` pairs. Supports GET/POST method differentiation. |
| **`config/`** | Centralized configuration. `database.php` holds DB credentials (**NFR-MNT-002**) and must be excluded from version control via `.gitignore`. `routes.php` defines all URL → Controller mappings. |
| **`helpers/`** | Procedural helper functions auto-loaded by the front controller. Includes HTML-escaping wrappers (`e()`), redirect utilities, CSRF token generation/verification, and input validation functions. |
| **`middleware/`** | Classes that run before controller methods to enforce access rules. `AuthMiddleware` checks for an active session, `AdminMiddleware` checks `role_id`, `GuestMiddleware` redirects already-authenticated users away from the login page. |
| **`database/migrations/`** | Sequentially numbered SQL files for creating each table. Enables reproducible schema setup and versioning per **NFR-MNT-004**. |
| **`database/seeders/`** | SQL scripts that insert initial data (admin account, room list) required before the System goes live. |
| **`uploads/`** | Stores user-uploaded images (products and profiles). Located **outside `public/`** to prevent direct URL access; files are served through a PHP script that validates access, or via a symlink/rewrite strategy. Sub-organized into `products/` and `profiles/`. |
| **`logs/`** | Stores application error logs. Not accessible from the web. |

### 6.3 Request Lifecycle

The following illustrates how an HTTP request flows through the MVC structure:

```
┌──────────────────────────────────────────────────────────────────────────┐
│                         CLIENT (Browser)                                │
│   GET /dashboard   or   POST /cart/add   or   GET /admin/products      │
└────────────────────────────────┬─────────────────────────────────────────┘
                                 │
                                 ▼
┌──────────────────────────────────────────────────────────────────────────┐
│  public/.htaccess                                                       │
│  RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]                           │
└────────────────────────────────┬─────────────────────────────────────────┘
                                 │
                                 ▼
┌──────────────────────────────────────────────────────────────────────────┐
│  public/index.php  (Front Controller)                                   │
│                                                                         │
│  1. require  config/app.php          — Load constants                   │
│  2. require  config/database.php     — Establish DB connection (PDO)    │
│  3. session_start()                  — Resume / create session          │
│  4. require  helpers/*.php           — Load helper functions            │
│  5. require  app/Router.php          — Initialize router                │
│  6. require  config/routes.php       — Register route definitions       │
│  7. $router->dispatch($_GET['url'])  — Match URL → Controller@method   │
└────────────────────────────────┬─────────────────────────────────────────┘
                                 │
                                 ▼
┌──────────────────────────────────────────────────────────────────────────┐
│  middleware/  (AuthMiddleware, AdminMiddleware, GuestMiddleware)         │
│  → Verify session / role → Redirect if unauthorized                     │
└────────────────────────────────┬─────────────────────────────────────────┘
                                 │ (authorized)
                                 ▼
┌──────────────────────────────────────────────────────────────────────────┐
│  app/Controllers/XxxController.php                                      │
│                                                                         │
│  1. Validate & sanitize input                                           │
│  2. Call  app/Services/XxxService.php  →  Business logic & transactions │
│  3. Pass result data to View                                            │
│  4. require  app/Views/xxx/page.php  (wrapped in layout)                │
└──────────────────┬──────────────────────────┬────────────────────────────┘
                   │                          │
                   ▼                          ▼
      ┌─────────────────────┐     ┌──────────────────────────┐
      │ app/Services/Xxx.php│     │  app/Views/xxx/page.php  │
      │  ↓ calls ↓          │     │  + layouts/ + partials/  │
      │ app/Models/Xxx.php  │     └──────────┬───────────────┘
      │  (SQL via PDO)      │
      └─────────────────────┘
                                             │
                                             ▼
                                  ┌──────────────────────┐
                                  │   HTML Response       │
                                  │   → Browser renders   │
                                  └──────────────────────┘
```
