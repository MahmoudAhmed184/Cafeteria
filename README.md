## Cafeteria Management System

This is a server-rendered Cafeteria Management System built with PHP 8.x, MySQL 8.x, HTML/CSS/JS, and an MVC‑Service architecture.

It digitizes product browsing, cart management, ordering, admin product/user management, and financial checks. Full functional and non‑functional requirements are described in:

- `docs/SRS_Cafeteria_Management_System.md`
- `docs/Project_Plan.md`
- `docs/Project Requirements.md`

### Tech stack

- PHP 8.x
- MySQL 8.x
- Vanilla JS (`public/assets/js`)
- CSS (global + component/page CSS)
- MVC‑Service structure (front controller + Router + Controllers + Services + Models + Views)

### Folder structure (high level)

- `public/` – webroot (front controller, assets)
- `app/Controllers/` – request controllers (not included in this UI‑only snapshot)
- `app/Services/` – business‑logic services
- `app/Models/` – PDO database models
- `app/Views/` – PHP templates for user/admin
- `app/Views/layouts/` – base layouts
- `app/Views/partials/` – shared UI partials
- `config/` – application and database configuration
- `docs/` – SRS and project planning documents

### Local setup

1. Install PHP 8.x, MySQL 8.x, and Composer (if you use it for dependencies).
2. Create a MySQL database (for example `cafeteria_db`) and a user with full rights.
3. Clone the repository and install dependencies if present:

   ```bash
   git clone <your-repo-url>
   cd Cafeteria
   ```

4. Configure application/database settings in `config/app.php` and `config/database.php` (or your team’s `.env` convention).
5. Run database migrations and seeders using your team’s migration scripts (for example a custom CLI script such as `php cli/migrate.php` and `php cli/seed.php`).
6. Point your web server’s document root to `Cafeteria/public` and enable URL rewriting so all requests go through the front controller.

For quick local testing you can also use PHP’s built‑in server from the `public` directory:

```bash
cd public
php -S localhost:8000
```

Then open `http://localhost:8000` in your browser.

### Development workflow

- Use feature branches per task (for example `feature/m4-layouts-views-login-dashboard`).
- Keep views thin: all data should come from controllers/services; views only render arrays passed in.
- Use `public/preview.php` only during UI development; production usage should go through the main router/front controller.

### Deployment (summary)

See `docs/deployment.md` for full details. At a high level:

1. Provision a server with PHP 8.x, required extensions (pdo_mysql, mbstring, intl, fileinfo), and MySQL 8.x.
2. Clone the repo to the server and install dependencies.
3. Configure environment (`APP_ENV=production`, database credentials, error logging).
4. Run migrations and seeders.
5. Point your web server (Apache/Nginx) to `public/` and enable URL rewriting.
6. Disable `display_errors` and enable error logging to a file (for example `storage/logs/error.log`).

