## Deployment Guide – Cafeteria Management System

This document describes how to deploy the Cafeteria Management System to a production‑like environment.

### 1. Environment requirements

- **Operating system**: Any OS capable of running PHP 8.x and MySQL 8.x (Linux recommended).
- **Web server**: Nginx or Apache with support for URL rewriting.
- **PHP**: 8.x with extensions:
  - `pdo_mysql`
  - `mbstring`
  - `intl`
  - `fileinfo`
  - `openssl`
- **Database**: MySQL 8.x (or compatible).

### 2. Application deployment steps

1. **Clone the repository**

   ```bash
   git clone <your-repo-url> /var/www/cafeteria
   cd /var/www/cafeteria/Cafeteria
   ```

2. **Configure environment**

   - Copy your team’s example configuration to a real config (for example `.env`, or edit `config/app.php` and `config/database.php`).
   - Set:
     - Application URL
     - Database host, name, user, password
     - Any mail/queue/logging options your team uses

3. **Install dependencies (if any)**

   - If Composer or other tools are used in this project, install PHP packages at this point:

   ```bash
   composer install --no-dev --optimize-autoloader
   ```

4. **Run database migrations and seeders**

   - Execute your migration/seeding scripts, for example:

   ```bash
   php cli/migrate.php
   php cli/seed.php
   ```

   - Ensure the admin user and reference data (rooms, categories) are created.

5. **Configure web server**

   - Set the document root to the `public/` directory, for example:
     - `/var/www/cafeteria/Cafeteria/public`
   - Enable URL rewriting so all non‑asset requests are routed to the front controller (`index.php`).
   - Serve static assets (`assets/css`, `assets/js`, `assets/images`) directly.

6. **Set file permissions**

   - Ensure the web server user can read application files and write to:
     - Log directory (for example `storage/logs` if present).
     - Upload directory for product/user images.

### 3. Production hardening

1. **PHP configuration**

   - Set:
     - `display_errors = Off`
     - `log_errors = On`
   - Configure `error_log` to point to a writable log file.

2. **Application configuration**

   - Set `APP_ENV=production` / equivalent.
   - Ensure CSRF protection is enabled on all state‑changing routes.
   - Use HTTPS in production and configure secure cookies if sessions are cookie‑based.

3. **Database**

   - Use a dedicated database user with least‑privilege access.
   - Create regular backups of the cafeteria database.

### 4. Updating the application

To deploy a new version:

1. Pull the latest code:

   ```bash
   cd /var/www/cafeteria
   git pull
   cd Cafeteria
   ```

2. Install/update dependencies:

   ```bash
   composer install --no-dev --optimize-autoloader
   ```

3. Run database migrations:

   ```bash
   php cli/migrate.php
   ```

4. Clear any application caches if the project uses them.

5. Reload/restart the web server if needed (for example after PHP‑FPM or config changes).

