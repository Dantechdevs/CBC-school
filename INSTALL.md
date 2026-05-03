# 🛠️ Installation Guide — CBC School Management System

Follow these steps **in order**. Do not skip any step.

---

## Prerequisites

| Requirement | Version | Where to get it |
|---|---|---|
| PHP | 8.2 or higher | XAMPP ships PHP 8.2+ |
| Composer | 2.x | https://getcomposer.org |
| MySQL | 8.0+ | Included in XAMPP |
| Node.js | 18+ | https://nodejs.org |
| npm | 9+ | Included with Node.js |

> **XAMPP users:** Start Apache and MySQL from the XAMPP Control Panel before proceeding.

---

## Step 1 — Create the Database

Open phpMyAdmin (http://localhost/phpmyadmin) or MySQL CLI and run:

```sql
CREATE DATABASE cbc_school_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
```

---

## Step 2 — Install PHP Dependencies

```bash
composer install
```

> If you see any errors, run: `composer install --ignore-platform-reqs`

---

## Step 3 — Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

Then open `.env` and update:

```env
DB_DATABASE=cbc_school_db
DB_USERNAME=root
DB_PASSWORD=          # leave blank for XAMPP default
```

> **Important:** The defaults in `.env.example` are already configured for XAMPP:
> - `CACHE_STORE=file` ✅
> - `SESSION_DRIVER=file` ✅
> - `QUEUE_CONNECTION=sync` ✅
>
> Do NOT change these to `redis` unless you have Redis installed.

---

## Step 4 — Run Migrations & Seed Data

```bash
php artisan migrate --seed
```

This creates all database tables and inserts:
- All roles and permissions
- Full CBC curriculum (learning areas, strands, sub-strands)
- 3 default user accounts (see logins below)

---

## Step 5 — Install Frontend Assets

```bash
npm install
npm run build
```

---

## Step 6 — Link Storage

```bash
php artisan storage:link
```

---

## Step 7 — Start the Server

```bash
php artisan serve
```

Visit: **http://localhost:8000**

---

## Default Login Accounts

| Role | Email | Password |
|---|---|---|
| Super Admin | admin@school.ac.ke | Admin@1234 |
| Principal | principal@school.ac.ke | Principal@1234 |
| Bursar | bursar@school.ac.ke | Bursar@1234 |

> ⚠️ **Change all default passwords immediately after first login.**

---

## Setting Up M-Pesa (When Ready)

1. Register at https://developer.safaricom.co.ke
2. Create a Daraja app — get Consumer Key and Secret
3. Update `.env`:
   ```env
   MPESA_ENV=sandbox
   MPESA_CONSUMER_KEY=your_key
   MPESA_CONSUMER_SECRET=your_secret
   MPESA_SHORTCODE=174379
   MPESA_PASSKEY=your_passkey
   ```
4. For the callback URL to work locally, use ngrok:
   ```bash
   ngrok http 8000
   # Copy the https URL into MPESA_CALLBACK_URL in .env
   ```

---

## Setting Up SMS (Africa's Talking)

1. Register at https://africastalking.com
2. Create an app — get API Key
3. Update `.env`:
   ```env
   AT_API_KEY=your_api_key
   AT_USERNAME=sandbox   # use your actual username in production
   AT_SENDER_ID=SCHOOL
   ```

---

## Switching from XAMPP to Production (Redis)

When deploying to a VPS with Redis installed:

```env
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

Then start the queue worker:

```bash
php artisan queue:work --queue=high,default,low
```

---

## Common Issues

| Problem | Solution |
|---|---|
| `php: command not found` | Add PHP to your PATH, or use the full path e.g. `C:\xampp\php\php.exe artisan serve` |
| `SQLSTATE: Access denied` | Check DB_USERNAME and DB_PASSWORD in .env |
| `Class 'Spatie\Permission\...' not found` | Run `composer install` again |
| Blank white page | Enable debug: `APP_DEBUG=true` in .env, then check `storage/logs/laravel.log` |
| `CACHE_STORE=redis` error | Change to `CACHE_STORE=file` — Redis is not available on XAMPP |
| No login page / 404 | Ensure `public/index.php` exists and your server points to the `public/` folder |

---

## XAMPP Virtual Host (Optional)

To access as `http://cbc-school.local` instead of `http://localhost:8000`:

1. Edit `C:\xampp\apache\conf\extra\httpd-vhosts.conf`:
   ```apache
   <VirtualHost *:80>
       ServerName cbc-school.local
       DocumentRoot "C:/xampp/htdocs/cbc-school/public"
       <Directory "C:/xampp/htdocs/cbc-school/public">
           Options Indexes FollowSymLinks
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```
2. Edit `C:\Windows\System32\drivers\etc\hosts`:
   ```
   127.0.0.1   cbc-school.local
   ```
3. Restart Apache from XAMPP Control Panel.

