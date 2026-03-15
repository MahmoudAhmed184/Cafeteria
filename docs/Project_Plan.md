# Project Execution Plan

## Cafeteria Management System

| Field              | Value                                  |
|--------------------|----------------------------------------|
| **Document**       | Project Execution Plan v1.0            |
| **Date**           | 2026-03-15                             |
| **Technology**     | PHP 8.x · MySQL 8.x · HTML/CSS/JS     |
| **Architecture**   | MVC-Service (Vanilla PHP)              |
| **Team Size**      | 5 Full-Stack Generalist Developers     |
| **Total Effort**   | 191 Story Points (Fibonacci)           |

---

## Table of Contents

- [1. Project Overview](#1-project-overview)
- [2. Execution Strategy & Decoupling](#2-execution-strategy--decoupling)
  - [2.1 Identified Bottlenecks](#21-identified-bottlenecks)
  - [2.2 Decoupling Strategies](#22-decoupling-strategies)
- [3. Master Task Breakdown](#3-master-task-breakdown)
  - [3.1 Epic 0 — Foundation](#31-epic-0--foundation)
  - [3.2 Epic 1 — Authentication](#32-epic-1--authentication)
  - [3.3 Epic 2 — User Dashboard & Cart](#33-epic-2--user-dashboard--cart)
  - [3.4 Epic 3 — Order Management (User)](#34-epic-3--order-management-user)
  - [3.5 Epic 4 — Admin Orders Dashboard](#35-epic-4--admin-orders-dashboard)
  - [3.6 Epic 5 — Admin Manual Order](#36-epic-5--admin-manual-order)
  - [3.7 Epic 6 — Admin Product Management](#37-epic-6--admin-product-management)
  - [3.8 Epic 7 — Admin User Management](#38-epic-7--admin-user-management)
  - [3.9 Epic 8 — Financial Checks / Reporting](#39-epic-8--financial-checks--reporting)
  - [3.10 Epic 9 — Integration, Polish & Hardening](#310-epic-9--integration-polish--hardening)
- [4. Team Workload Distribution](#4-team-workload-distribution)
  - [4.1 Member Assignments](#41-member-assignments)
  - [4.2 Equity Verification](#42-equity-verification)
- [5. Execution Timeline](#5-execution-timeline)

---

## 1. Project Overview

The **Cafeteria Management System** is a self-contained, server-rendered web application that digitizes the ordering workflow for an organizational cafeteria. It exposes a product catalogue to employees, lets them build shopping carts with real-time AJAX interactions, place delivery orders to specific rooms, and track order history. Administrators manage products, categories, user accounts, process incoming orders, place manual orders on behalf of users, and generate three-level drill-down financial reports.

The architecture follows an **MVC-Service** pattern in vanilla PHP. A single front controller (`public/index.php`) dispatches requests through middleware guards to thin controllers, which delegate business logic to service classes. Services interact with model classes that encapsulate all PDO-based prepared-statement queries. Views are PHP/HTML templates organized by role.

**Core Capabilities:**

| Domain         | User Features                              | Admin Features                                  |
|----------------|--------------------------------------------|-------------------------------------------------|
| **Products**   | Browse catalogue, search, add to cart      | CRUD products, manage categories, toggle availability |
| **Orders**     | Place orders, cancel (if Processing), view history | Process/deliver orders, place manual orders for users |
| **Accounts**   | Login, logout, password recovery           | CRUD user accounts, soft-delete                 |
| **Reporting**  | —                                          | Three-level financial drill-down (user → order → item) |

**Key Constraints:**

- Session-based authentication (no JWT) with bcrypt password hashing
- CSRF protection on all state-changing operations
- Prepared statements (SQL injection prevention) and `htmlspecialchars` output escaping (XSS prevention)
- Secure file uploads with MIME validation, size limit, and UUID renaming
- Atomic database transactions for order placement
- Responsive design (≥ 768 px), sub-3s page loads, sub-500ms AJAX responses

> **Reference:** Full requirements are specified in [`docs/SRS_Cafeteria_Management_System.md`](docs/SRS_Cafeteria_Management_System.md) and [`docs/Project Requirements.md`](docs/Project%20Requirements.md).

---

## 2. Execution Strategy & Decoupling

### 2.1 Identified Bottlenecks

Analysis of the initial sequential plan revealed critical waterfall dependencies, including a maximum chain depth of **6 levels** and only **2 tasks** startable on Day 1. The following bottlenecks were identified:

| ID   | Bottleneck                      | Root Cause                                                          | Cascading Impact                  |
|------|---------------------------------|---------------------------------------------------------------------|-----------------------------------|
| B1   | DB Schema blocks all models     | Models wait for SQL migrations to be merged and executed.           | Cascades to 14+ downstream tasks  |
| B2   | Project Skeleton blocks 6 tasks | Helpers, middleware, layouts all wait on `index.php` bootstrap.     | 3 team members idle on Day 1      |
| B3   | Router blocks all controllers   | No controller can be dispatched without `Router.php`.               | All feature work delayed           |
| B4   | View ↔ Controller coupling      | Every view depends on its controller being code-complete.           | All 10 view tasks perpetually blocked |
| B5   | CartService is a 4-way bottleneck | Cart widget, cart controller, OrderService, and manual order all depend on it. | Core ordering pipeline stalled |
| B6   | OrderService is a 3-way bottleneck | User order controller, admin order controller, manual order depend on it. | Admin features can't start |
| B7   | User Model is a 4-way fan-out  | Auth, UserService, CheckService, & manual order all depend on it.   | Cross-epic cascading delays        |
| B8   | Epic 9 waterfall gate           | All polish tasks wait for "All Epics 1–8."                         | Entire team idle before polish     |

### 2.2 Decoupling Strategies

Six strategies were applied to reduce chain depth from **6 → 2 levels** and increase Day-1 startable tasks from **2 → 46**:

**Strategy 1 — Schema-First Contract** *(kills B1)*
> M1 publishes a `schema_contract.md` on Day 1, Hour 1 — listing every table, column, data type, and constraint (sourced directly from SRS §4.2). Model developers treat this contract as their API and code PDO queries against column names immediately. The actual `.sql` migration files are a parallel deliverable, not a prerequisite.

**Strategy 2 — Skeleton-as-Convention** *(kills B2)*
> The directory structure and file-naming conventions are published as documentation on Day 1 (fully specified in SRS §6.1). Each developer creates files in agreed-upon directories from the start. Helpers, middleware, and layouts are self-contained PHP files that do not import the skeleton.

**Strategy 3 — Interface Contracts + Stub Returns** *(kills B3, B5, B6, B7)*
> PHP interface files are defined for every Service on Day 1, declaring public method signatures, parameter types, and return types. Developers code against interfaces, not concrete classes. Controllers and services are developed independently and in parallel.

| Interface                 | Key Methods                                                        | Owner |
|---------------------------|--------------------------------------------------------------------|-------|
| `AuthServiceInterface`    | `authenticate()`, `logout()`, `initiatePasswordReset()`            | M1    |
| `CartServiceInterface`    | `addItem()`, `updateQty()`, `removeItem()`, `getCart()`, `clearCart()` | M2 |
| `OrderServiceInterface`   | `createOrder()`, `cancelOrder()`, `getOrdersByUser()`, `getOrderDetails()` | M3 |
| `ProductServiceInterface` | `getAll()`, `create()`, `update()`, `delete()`, `toggleAvailability()` | M5 |
| `UserServiceInterface`    | `create()`, `update()`, `softDelete()`, `getAll()`                 | M3    |
| `CheckServiceInterface`   | `getUserSummary()`, `getUserOrders()`, `getOrderItems()`           | M3    |

**Strategy 4 — View-First with Mock Data Arrays** *(kills B4)*
> PHP views are templates that receive `$data` arrays. During development, each view includes a hard-coded mock `$data` block at the top. Views are buildable from the schema contract alone. When controllers are ready, the mock block is deleted.

**Strategy 5 — Router-Agnostic Controller Development** *(kills B3 for controllers)*
> Controllers are plain PHP classes with public methods, testable via direct method invocation. The Router is a thin mapping layer registered last via `config/routes.php`.

**Strategy 6 — Progressive Polish** *(kills B8)*
> Security hardening is embedded into helper functions from Day 1. Responsive design uses the base CSS system. Form validation is built inline with each view. Each polish task starts as soon as its target area is code-complete.

**Impact Summary:**

| Metric                    | Original | Decoupled | Improvement       |
|---------------------------|----------|-----------|-------------------|
| Max chain depth           | 6        | 2         | **67% reduction** |
| Total dependency edges    | 58       | 12        | **79% reduction** |
| Tasks startable on Day 1  | 2        | 46        | **23× increase**  |
| Blocked-member-days (est) | ~18      | ~2        | **89% reduction** |

---

## 3. Master Task Breakdown

> **Notation:** Task `0.0` is the team's sole synchronization point. Dependency `0.0` means the task is immediately startable after the kickoff session. `0.10` refers to the file-upload utility (Epic 0, task 10).

### 3.1 Epic 0 — Foundation

| #    | Task                                                            | Dependencies | Pts | Assignee |
|------|-----------------------------------------------------------------|--------------|-----|----------|
| 0.0  | **Schema contract + service interface contracts** — Publish `schema_contract.md` and 6 service interface PHP files. 2-hour kickoff. | None | 2 | ALL |
| 0.1  | **Database migration SQL files** — 6 `CREATE TABLE` scripts with indexes on `orders.user_id`, `orders.created_at`, `orders.status`. | 0.0 | 3 | M1 |
| 0.2  | **Database seeders** — Admin account (bcrypt) + rooms seeder with sample data. | 0.0 | 2 | M1 |
| 0.3  | **Project skeleton & front controller** — Directory structure per SRS §6, `index.php` bootstrap, `.htaccess`. | 0.0 | 3 | M2 |
| 0.4  | **Router implementation** — `Router.php` with GET/POST dispatch + `routes.php` definitions. | 0.0 | 5 | M2 |
| 0.5  | **Configuration files** — `config/app.php`, `config/database.php`, `.gitignore`. | 0.0 | 2 | M2 |
| 0.6  | **Helper functions** — `functions.php` (`redirect()`, `asset()`, `old()`, `e()`), `validation.php`, `csrf.php`. Security by default. | 0.0 | 3 | M3 |
| 0.7  | **Middleware layer** — `AuthMiddleware`, `AdminMiddleware`, `GuestMiddleware`. | 0.0 | 3 | M3 |
| 0.8  | **Base layouts, partials & global CSS** — `app.php`, `auth.php` layouts; header, sidebar, footer, toast partials; all CSS files. | 0.0 | 5 | M4 |
| 0.9  | **Global JS utilities** — `app.js` (AJAX helpers, CSRF token injection). | 0.0 | 2 | M4 |
| 0.10 | **File upload utility** — MIME validation (`finfo_file`), size limit, format restriction, UUID renaming, `.htaccess` engine-off. | 0.0 | 3 | M5 |
| 0.11 | **Error logging setup** — Custom error handler, `logs/error.log`, suppress display to end users. | 0.0 | 2 | M5 |

### 3.2 Epic 1 — Authentication

*SRS §3.1 · FR-AUTH-001 → 008*

| #   | Task                                                            | Dependencies | Pts | Assignee |
|-----|-----------------------------------------------------------------|--------------|-----|----------|
| 1.1 | **User model** — `findByEmail()`, `findById()`, `create()`, `update()`, `softDelete()`, `getAll()`. PDO prepared statements. | 0.0 | 3 | M1 |
| 1.2 | **Auth service & controller** — Credential verification, session management, session regeneration, login/logout actions. | 0.0 | 5 | M1 |
| 1.3 | **Login view** — Email/password form, error display, "Forget Password?" link. `auth.css`. Mock `$data`. | 0.0 | 3 | M4 |
| 1.4 | **Forget password flow** — View + controller + service logic (token-reset stub). | 0.0 | 3 | M5 |

### 3.3 Epic 2 — User Dashboard & Cart

*SRS §3.2 · FR-CART-001 → 012*

| #   | Task                                                            | Dependencies | Pts | Assignee |
|-----|-----------------------------------------------------------------|--------------|-----|----------|
| 2.1 | **Product & Category models** — `getAvailable()`, CRUD for products; `getAll()`, `create()` for categories. | 0.0 | 3 | M3 |
| 2.2 | **Room model** — `getAll()`. | 0.0 | 1 | M3 |
| 2.3 | **Dashboard controller + CartService** — Load products/rooms, manage session-based cart state. | 0.0 | 5 | M2 |
| 2.4 | **Dashboard view** — Product grid (image, name, price), search bar, room dropdown, notes textarea, confirm button. `dashboard.css`. Mock `$data`. | 0.0 | 5 | M4 |
| 2.5 | **Cart widget + `cart.js`** — AJAX add/+/−/remove, line totals, grand total recalculation. JSON contract from `CartServiceInterface`. | 0.0, 0.9 | 8 | M2 |
| 2.6 | **Cart controller (AJAX endpoints)** — `add()`, `update()`, `remove()`, `getCart()` — all return JSON. | 0.0 | 5 | M2 |
| 2.7 | **Product search / filter** — `search.js` client-side filtering by product name. | 0.0 | 3 | M4 |
| 2.8 | **Latest order widget** — Most recent order summary. Mock data; wired during integration. | 0.0 | 2 | M4 |

### 3.4 Epic 3 — Order Management (User)

*SRS §3.3 · FR-ORD-001 → 007*

| #   | Task                                                            | Dependencies | Pts | Assignee |
|-----|-----------------------------------------------------------------|--------------|-----|----------|
| 3.1 | **Order & OrderItem models** — `create()`, status transitions, `getByUser()`, batch insert, `getByOrder()`. | 0.0 | 5 | M3 |
| 3.2 | **OrderService — creation & cancellation** — Atomic transaction (price snapshot capture), cancellation with server-side status guard (race-condition safe). | 0.0 | 8 | M3 |
| 3.3 | **Order controller (user side)** — `index()` (paginated), `cancel()`, `details()` (AJAX). Codes against `OrderServiceInterface`. | 0.0 | 5 | M1 |
| 3.4 | **My Orders view + `orders.js`** — Orders table, date-range filters, pagination, expandable rows, cancel button. Mock `$data`. | 0.0 | 5 | M5 |
| 3.5 | **Pagination partial** — Reusable `partials/pagination.php` (Previous / Page # / Next). | 0.0 | 2 | M5 |

### 3.5 Epic 4 — Admin Orders Dashboard

*SRS §3.4 · FR-ADM-ORD-001 → 004*

| #   | Task                                                            | Dependencies | Pts | Assignee |
|-----|-----------------------------------------------------------------|--------------|-----|----------|
| 4.1 | **Admin order controller & service** — Incoming orders list, deliver/done actions. Status transitions: `Processing → Out for Delivery → Done`. | 0.0 | 5 | M1 |
| 4.2 | **Admin orders view** — Table (date, user, room, ext, amount, action), deliver/done buttons, expandable rows. Mock `$data`. | 0.0 | 5 | M5 |

### 3.6 Epic 5 — Admin Manual Order

*SRS §3.5 · FR-ADM-MAN-001 → 003*

| #   | Task                                                            | Dependencies | Pts | Assignee |
|-----|-----------------------------------------------------------------|--------------|-----|----------|
| 5.1 | **Manual order controller & service** — User search endpoint, order placement with injected `user_id`. Codes against `CartServiceInterface` + `OrderServiceInterface`. | 0.0 | 5 | M3 |
| 5.2 | **Manual order view** — Searchable "Add to User" dropdown, product catalogue, cart widget, room/notes/confirm. Mock `$data`. | 0.0 | 5 | M4 |

### 3.7 Epic 6 — Admin Product Management

*SRS §3.6 · FR-ADM-PRD-001 → 007*

| #    | Task                                                            | Dependencies | Pts | Assignee |
|------|-----------------------------------------------------------------|--------------|-----|----------|
| 6.1  | **ProductService** — CRUD rules, availability toggle, image handling (upload + delete), soft-delete guard for referenced products. | 0.0, 0.10 | 5 | M5 |
| 6.2  | **Product controller (admin)** — Index, create, store, edit, update, delete, toggle availability, add-category AJAX. | 0.0 | 5 | M1 |
| 6.3  | **Products list view** — Table (name, price, thumbnail, availability toggle, edit/delete). Mock `$data`. | 0.0 | 3 | M2 |
| 6.4  | **Add/Edit product form + `products.js`** — Name, price, category dropdown, image upload with preview, "Add Category" modal, save/reset. | 0.0 | 5 | M2 |

### 3.8 Epic 7 — Admin User Management

*SRS §3.7 · FR-ADM-USR-001 → 006*

| #   | Task                                                            | Dependencies | Pts | Assignee |
|-----|-----------------------------------------------------------------|--------------|-----|----------|
| 7.1 | **UserService** — CRUD rules, password hashing, soft-delete for users with orders, profile pic handling. | 0.0, 0.10 | 5 | M3 |
| 7.2 | **User controller (admin)** — Index, create, store, edit, update, delete. Codes against `UserServiceInterface`. | 0.0 | 3 | M1 |
| 7.3 | **Users list view** — Table (name, room, thumbnail, ext, edit/delete). Mock `$data`. | 0.0 | 3 | M5 |
| 7.4 | **Add/Edit user form + `users.js`** — Name, email, password, confirm password, room dropdown, ext, profile pic upload, save/reset. | 0.0 | 3 | M2 |

### 3.9 Epic 8 — Financial Checks / Reporting

*SRS §3.8 · FR-ADM-CHK-001 → 006*

| #   | Task                                                            | Dependencies | Pts | Assignee |
|-----|-----------------------------------------------------------------|--------------|-----|----------|
| 8.1 | **CheckService** — Three-level drill-down: Level 1 (user summary), Level 2 (user orders), Level 3 (item breakdown). Excludes `Cancelled` orders. | 0.0 | 8 | M3 |
| 8.2 | **Check controller** — Index (filters), user-detail AJAX, order-detail AJAX. Codes against `CheckServiceInterface`. | 0.0 | 3 | M1 |
| 8.3 | **Checks view + `checks.js`** — Date-range filters, user dropdown, summary table, expandable user → order → item rows. Mock `$data`. | 0.0 | 5 | M5 |

### 3.10 Epic 9 — Integration, Polish & Hardening

| #   | Task                                                            | Dependencies   | Pts | Assignee |
|-----|-----------------------------------------------------------------|----------------|-----|----------|
| 9.0 | **Full-stack integration** — Wire views to controllers, replace mock `$data`, register all routes, end-to-end smoke test. | Epics 0–8 | 5 | ALL |
| 9.1 | **Security audit** — CSRF enforcement, escaping audit, prepared-statement audit, session flag verification. | 0.6 | 3 | M3 |
| 9.2 | **Client-side form validation** — HTML5 + JS validation on all forms, visual feedback (toasts/spinners). | Views complete | 3 | M4 |
| 9.3 | **Responsive design pass** — All views ≥ 768 px, focus/hover states on interactive elements. | 0.8 | 3 | M2 |
| 9.4 | **Performance optimization** — Image thumbnail generation, database index verification, query profiling for checks. | 0.1 | 3 | M5 |
| 9.5 | **README & deployment docs** — Setup instructions, environment requirements, migration/seeder commands. | 0.0 | 2 | M4 |

---

## 4. Team Workload Distribution

### 4.1 Member Assignments

#### Mahmoud Ahmed (M1) — 37 points

> **Focus:** Database materialization, authentication backbone, and admin controller layer.

| Task | Description                       | Pts |
|------|-----------------------------------|-----|
| 0.0  | Schema + interface contracts      | 2   |
| 0.1  | Database migrations               | 3   |
| 0.2  | Database seeders                  | 2   |
| 1.1  | User model                        | 3   |
| 1.2  | Auth service & controller         | 5   |
| 3.3  | Order controller (user)           | 5   |
| 4.1  | Admin order controller & service  | 5   |
| 6.2  | Product controller (admin)        | 5   |
| 7.2  | User controller (admin)           | 3   |
| 8.2  | Check controller                  | 3   |
| 9.0  | Integration (shared)              | 1   |

---

#### Karim Muhammed (M2) — 39 points

> **Focus:** Application infrastructure, cart engine, and admin list views.

| Task | Description                       | Pts |
|------|-----------------------------------|-----|
| 0.0  | Schema + interface contracts      | 2   |
| 0.3  | Project skeleton & front controller | 3 |
| 0.4  | Router implementation             | 5   |
| 0.5  | Configuration files               | 2   |
| 2.3  | Dashboard controller + CartService | 5  |
| 2.5  | Cart widget + `cart.js`           | 8   |
| 2.6  | Cart controller (AJAX)            | 5   |
| 6.3  | Products list view                | 3   |
| 7.4  | Add/Edit user form view           | 3   |
| 9.3  | Responsive design pass            | 3   |

---

#### Deiaa Mohammed (M3) — 39 points

> **Focus:** Core models, business-logic services, and security hardening.

| Task | Description                       | Pts |
|------|-----------------------------------|-----|
| 0.0  | Schema + interface contracts      | 2   |
| 0.6  | Helper functions                  | 3   |
| 0.7  | Middleware layer                  | 3   |
| 2.1  | Product & Category models         | 3   |
| 2.2  | Room model                        | 1   |
| 3.1  | Order & OrderItem models          | 5   |
| 3.2  | OrderService                      | 8   |
| 5.1  | Manual order controller & service | 5   |
| 7.1  | UserService                       | 5   |
| 9.0  | Integration (shared)              | 1   |
| 9.1  | Security audit                    | 3   |

---

#### Amr Abokhaled (M4) — 38 points

> **Focus:** Complete UI/view layer, design system, and client-side polish.

| Task | Description                       | Pts |
|------|-----------------------------------|-----|
| 0.0  | Schema + interface contracts      | 2   |
| 0.8  | Layouts, partials & CSS           | 5   |
| 0.9  | Global JS utilities               | 2   |
| 1.3  | Login view                        | 3   |
| 2.4  | Dashboard view                    | 5   |
| 2.7  | Product search/filter JS          | 3   |
| 2.8  | Latest order widget               | 2   |
| 5.2  | Manual order view                 | 5   |
| 6.4  | Add/Edit product form view        | 5   |
| 9.0  | Integration (shared)              | 1   |
| 9.2  | Client-side form validation       | 3   |
| 9.5  | README & deployment docs          | 2   |

---

#### Ebram Shaker (M5) — 38 points

> **Focus:** File handling, order/admin views, reporting UI, and performance.

| Task | Description                       | Pts |
|------|-----------------------------------|-----|
| 0.0  | Schema + interface contracts      | 2   |
| 0.10 | File upload utility              | 3   |
| 0.11 | Error logging setup              | 2   |
| 1.4  | Forget password flow             | 3   |
| 3.4  | My Orders view + `orders.js`     | 5   |
| 3.5  | Pagination partial               | 2   |
| 4.2  | Admin orders view                | 5   |
| 6.1  | ProductService                   | 5   |
| 7.3  | Users list view                  | 3   |
| 8.3  | Checks view + `checks.js`       | 5   |
| 9.4  | Performance optimization         | 3   |

---

### 4.2 Equity Verification

| Member | Total Points | Deviation from Average |
|--------|-------------|----------------------|
| M1     | 37          | −1.2 pts (3.1%)      |
| M2     | 39          | +0.8 pts (2.1%)      |
| M3     | 39          | +0.8 pts (2.1%)      |
| M4     | 38          | −0.2 pts (0.5%)      |
| M5     | 38          | −0.2 pts (0.5%)      |

| Metric               | Value                                |
|----------------------|--------------------------------------|
| **Grand Total**      | 191 points                           |
| **Team Average**     | 38.2 points                          |
| **Max Deviation**    | 3.1% (M1) — well within the ≤ 10% threshold |

**SRS Coverage:** All 8 system features, 38 functional requirements (FR-AUTH through FR-ADM-CHK), 24 non-functional requirements (NFR-SEC through NFR-MNT), and the full MVC-Service architectural mandate are accounted for in the task list above.

---

## 5. Execution Timeline

The decoupled plan enables **all 5 members to work in parallel from Day 1**, with only two synchronization points: the kickoff contract session (Task 0.0) and the final integration sprint (Task 9.0).

```
Sprint 1 ─────────────────────────────────────────────────────────────
 Day 1      │ 0.0 Kickoff (ALL, 2 hours)
 Day 2–3    │ M1: 0.1, 0.2, 1.1, 1.2     (DB + Auth)
            │ M2: 0.3, 0.4, 0.5           (Infrastructure)
            │ M3: 0.6, 0.7, 2.1, 2.2      (Helpers + Models)
            │ M4: 0.8, 0.9, 1.3, 2.4      (Layouts + Views)
            │ M5: 0.10, 0.11, 1.4, 3.5    (Utilities + Views)

Sprint 2 ─────────────────────────────────────────────────────────────
 Day 4–5   │ M1: 3.3, 4.1, 6.2           (Controllers)
            │ M2: 2.3, 2.5, 2.6           (Cart Engine)
            │ M3: 3.1, 3.2, 5.1           (Order Logic)
            │ M4: 2.7, 2.8, 5.2, 6.4      (Views)
            │ M5: 3.4, 4.2, 6.1           (Views + Services)

Sprint 3 ─────────────────────────────────────────────────────────────
 Day 6–9  │ M1: 7.2, 8.2                (Controllers)
            │ M2: 6.3, 7.4, 9.3           (Views + Responsive)
            │ M3: 7.1, 8.1, 9.1           (Services + Security)
            │ M4: 9.2, 9.5                (Validation + Docs)
            │ M5: 7.3, 8.3, 9.4           (Views + Performance)

Sprint 4 ─────────────────────────────────────────────────────────────
 Day 10  │ 9.0 Full Integration (ALL)
            │ End-to-end smoke testing
            │ Bug fixes & final polish
```