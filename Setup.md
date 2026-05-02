# 🚀 CBC School Management System — Laravel 12 Setup Guide

> Laravel 12 | PHP 8.2+ | MySQL 8.0 | Livewire 3 | Tailwind CSS

---

## ✅ Why Laravel 12?

| Version | Status | Recommendation |
|---|---|---|
| Laravel 13 | Released March 2026 | ⚠️ Too new — ecosystem still catching up |
| **Laravel 12** | Released Feb 2025 | ✅ **Use this — stable + full package support** |
| Laravel 11 | Security fixes only | ❌ Avoid for new projects |

---

## 📦 Step 1 — Requirements Check

Make sure you have these installed before starting:

```bash
php -v         # Must be PHP 8.2 or higher
composer -v    # Must be Composer 2.x
node -v        # Must be Node.js 18+
npm -v
mysql --version # MySQL 8.0+
redis-cli ping  # Should return PONG
```

Install PHP 8.2+ on Ubuntu/Debian:
```bash
sudo apt update
sudo apt install php8.2 php8.2-cli php8.2-mbstring php8.2-xml \
  php8.2-bcmath php8.2-curl php8.2-mysql php8.2-zip php8.2-redis \
  php8.2-gd unzip curl git
```

Install PHP 8.2+ on macOS (Homebrew):
```bash
brew install php@8.2
brew install mysql redis node
```

---

## 🛠️ Step 2 — Install Laravel 12

```bash
# Option A: Laravel Installer (recommended)
composer global require laravel/installer
laravel new cbc-school --git

# Option B: Composer directly
composer create-project laravel/laravel:^12.0 cbc-school
cd cbc-school
```

When prompted during `laravel new`, choose:
- **Starter kit:** None (we'll add Breeze manually)
- **Testing framework:** Pest
- **Database:** MySQL

---

## 📁 Step 3 — Install Core Packages

```bash
cd cbc-school

# Authentication + UI scaffolding
composer require laravel/breeze --dev
php artisan breeze:install livewire

# Roles & Permissions
composer require spatie/laravel-permission

# PDF Generation
composer require barryvdh/laravel-dompdf

# Excel/CSV Export
composer require maatwebsite/excel

# Media/File uploads
composer require spatie/laravel-medialibrary

# Activity logging (audit trail)
composer require spatie/laravel-activitylog

# Settings management
composer require spatie/laravel-settings

# Backup
composer require spatie/laravel-backup

# Africa's Talking (SMS)
composer require africastalking/africastalking

# M-Pesa Daraja
composer require safaricom/mpesa

# Image processing
composer require intervention/image

# QR Codes (for student IDs)
composer require simplesoftwareio/simple-qrcode

# Charts (backend data)
composer require flowframe/laravel-trend
```

Install Node packages:
```bash
npm install
npm install @alpinejs/persist @alpinejs/focus
npm run build
```

---

## ⚙️ Step 4 — Environment Configuration

Copy and edit your `.env` file:
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:
```env
APP_NAME="CBC School Management System"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
APP_TIMEZONE=Africa/Nairobi
APP_LOCALE=en

# -----------------------------------------------
# DATABASE
# -----------------------------------------------
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cbc_school_db
DB_USERNAME=root
DB_PASSWORD=your_password

# -----------------------------------------------
# QUEUE & CACHE (Redis)
# -----------------------------------------------
CACHE_STORE=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# -----------------------------------------------
# MAIL
# -----------------------------------------------
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=your_mailgun_user
MAIL_PASSWORD=your_mailgun_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourschool.ac.ke"
MAIL_FROM_NAME="${APP_NAME}"

# -----------------------------------------------
# AFRICA'S TALKING (SMS)
# -----------------------------------------------
AT_API_KEY=your_api_key_here
AT_USERNAME=sandbox
AT_SENDER_ID=SCHOOL
AT_ENV=sandbox

# -----------------------------------------------
# SAFARICOM M-PESA DARAJA
# -----------------------------------------------
MPESA_ENV=sandbox
MPESA_CONSUMER_KEY=your_consumer_key
MPESA_CONSUMER_SECRET=your_consumer_secret
MPESA_SHORTCODE=174379
MPESA_PASSKEY=your_lipa_na_mpesa_passkey
MPESA_CALLBACK_URL="${APP_URL}/api/mpesa/callback"
MPESA_CONFIRMATION_URL="${APP_URL}/api/mpesa/confirmation"
MPESA_VALIDATION_URL="${APP_URL}/api/mpesa/validation"

# -----------------------------------------------
# FIREBASE (Push Notifications)
# -----------------------------------------------
FIREBASE_SERVER_KEY=your_firebase_server_key
FIREBASE_PROJECT_ID=your_project_id

# -----------------------------------------------
# FILE STORAGE
# -----------------------------------------------
FILESYSTEM_DISK=local
# Change to 's3' for production with AWS/DigitalOcean Spaces

# -----------------------------------------------
# KEMIS API (when available)
# -----------------------------------------------
KEMIS_API_URL=https://kemis.education.go.ke/api
KEMIS_API_KEY=your_kemis_api_key
KEMIS_SCHOOL_CODE=your_school_code
```

---

## 🗂️ Step 5 — Publish Package Configs

```bash
# Spatie Permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"

# DomPDF
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"

# Media Library
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="medialibrary-migrations"

# Activity Log
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"

# Backup
php artisan vendor:publish --provider="Spatie\Backup\BackupServiceProvider"
```

---

## 🏗️ Step 6 — Project Folder Structure

After setup, organise your app like this:

```
cbc-school/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   ├── Teacher/
│   │   │   ├── Parent/
│   │   │   └── Api/
│   │   └── Middleware/
│   ├── Livewire/
│   │   ├── Students/
│   │   ├── Assessment/
│   │   ├── Fees/
│   │   ├── Inventory/
│   │   ├── Exams/
│   │   ├── Notes/
│   │   ├── Notifications/
│   │   ├── Timetable/
│   │   └── Reports/
│   ├── Models/
│   │   ├── Learner.php
│   │   ├── Guardian.php
│   │   ├── SchoolClass.php
│   │   ├── LearningArea.php
│   │   ├── Strand.php
│   │   ├── SubStrand.php
│   │   ├── Assessment.php
│   │   ├── ExamResult.php
│   │   ├── FeeStructure.php
│   │   ├── FeeInvoice.php
│   │   ├── FeePayment.php
│   │   ├── InventoryItem.php
│   │   ├── StaffMember.php
│   │   ├── TimetableSlot.php
│   │   └── Notification.php
│   ├── Services/
│   │   ├── AfricasTalkingService.php
│   │   ├── MpesaService.php
│   │   ├── FirebaseService.php
│   │   ├── KemisService.php
│   │   ├── ReportCardService.php
│   │   └── AssessmentService.php
│   ├── Jobs/
│   │   ├── SendSmsJob.php
│   │   ├── SendEmailJob.php
│   │   ├── GenerateReportCardJob.php
│   │   └── SyncToKemisJob.php
│   └── Enums/
│       ├── RubricLevel.php       # EE, ME, AE, BE
│       ├── GradeLevel.php        # PP1 to Grade12
│       ├── TermEnum.php          # Term1, Term2, Term3
│       └── PaymentMethod.php     # Mpesa, Bank, Cash
├── database/
│   ├── migrations/
│   │   ├── learners
│   │   ├── classes
│   │   ├── learning_areas
│   │   ├── assessments
│   │   ├── fees
│   │   ├── inventory
│   │   └── ...
│   └── seeders/
│       ├── RolesAndPermissionsSeeder.php
│       ├── GradeLevelsSeeder.php
│       ├── LearningAreasSeeder.php
│       └── AdminUserSeeder.php
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── app.blade.php
│   │   │   ├── admin.blade.php
│   │   │   ├── teacher.blade.php
│   │   │   └── parent.blade.php
│   │   ├── livewire/
│   │   ├── pdf/
│   │   │   ├── report-card.blade.php
│   │   │   ├── fee-receipt.blade.php
│   │   │   └── fee-statement.blade.php
│   │   └── emails/
│   └── js/
│       └── app.js
└── routes/
    ├── web.php
    ├── admin.php
    ├── teacher.php
    ├── parent.php
    └── api.php
```

---

## 🔐 Step 7 — Roles & Permissions Setup

Create the seeder file `database/seeders/RolesAndPermissionsSeeder.php`:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define all permissions
        $permissions = [
            // Students
            'view students', 'create students', 'edit students', 'delete students',
            // Assessment
            'view assessments', 'create assessments', 'edit assessments',
            // Report Cards
            'view report cards', 'generate report cards',
            // Fees
            'view fees', 'manage fees', 'record payments', 'view finance reports',
            // Inventory
            'view inventory', 'manage inventory',
            // Staff
            'view staff', 'manage staff',
            // Timetable
            'view timetable', 'manage timetable',
            // Notes & Curriculum
            'view notes', 'upload notes', 'manage curriculum',
            // Exams
            'view exams', 'manage exams', 'enter marks',
            // Notifications
            'send notifications',
            // KEMIS
            'sync kemis',
            // Analytics
            'view analytics',
            // System
            'manage system settings', 'manage users',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $roles = [
            'super-admin'     => $permissions, // all
            'principal'       => array_diff($permissions, ['manage system settings', 'manage users']),
            'deputy-principal'=> ['view students', 'view assessments', 'view timetable', 'manage timetable', 'view notes', 'view exams', 'view analytics'],
            'hod'             => ['view students', 'view assessments', 'create assessments', 'edit assessments', 'view notes', 'upload notes', 'manage curriculum', 'view exams', 'manage exams'],
            'class-teacher'   => ['view students', 'view assessments', 'create assessments', 'edit assessments', 'view notes', 'upload notes', 'view timetable', 'enter marks'],
            'teacher'         => ['view students', 'view assessments', 'create assessments', 'view notes', 'upload notes', 'view timetable', 'enter marks'],
            'bursar'          => ['view students', 'view fees', 'manage fees', 'record payments', 'view finance reports', 'view inventory', 'manage inventory'],
            'librarian'       => ['view inventory', 'manage inventory'],
            'storekeeper'     => ['view inventory', 'manage inventory'],
            'parent'          => ['view report cards', 'view notes', 'view fees'],
            'learner'         => ['view notes', 'view timetable'],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($rolePermissions);
        }
    }
}
```

---

## 🌱 Step 8 — Core Enums

Create `app/Enums/RubricLevel.php`:

```php
<?php

namespace App\Enums;

enum RubricLevel: string
{
    case ExceedsExpectation  = 'EE';
    case MeetsExpectation    = 'ME';
    case ApproachesExpectation = 'AE';
    case BelowExpectation    = 'BE';

    public function label(): string
    {
        return match($this) {
            self::ExceedsExpectation   => 'Exceeds Expectation',
            self::MeetsExpectation     => 'Meets Expectation',
            self::ApproachesExpectation => 'Approaches Expectation',
            self::BelowExpectation     => 'Below Expectation',
        };
    }

    public function numericValue(): int
    {
        return match($this) {
            self::ExceedsExpectation   => 4,
            self::MeetsExpectation     => 3,
            self::ApproachesExpectation => 2,
            self::BelowExpectation     => 1,
        };
    }

    public function color(): string
    {
        return match($this) {
            self::ExceedsExpectation   => 'green',
            self::MeetsExpectation     => 'blue',
            self::ApproachesExpectation => 'yellow',
            self::BelowExpectation     => 'red',
        };
    }
}
```

Create `app/Enums/GradeLevel.php`:

```php
<?php

namespace App\Enums;

enum GradeLevel: string
{
    case PP1 = 'PP1';
    case PP2 = 'PP2';
    case Grade1 = 'Grade 1';
    case Grade2 = 'Grade 2';
    case Grade3 = 'Grade 3';
    case Grade4 = 'Grade 4';
    case Grade5 = 'Grade 5';
    case Grade6 = 'Grade 6';
    case Grade7 = 'Grade 7';
    case Grade8 = 'Grade 8';
    case Grade9 = 'Grade 9';
    case Grade10 = 'Grade 10';
    case Grade11 = 'Grade 11';
    case Grade12 = 'Grade 12';

    public function level(): string
    {
        return match(true) {
            in_array($this, [self::PP1, self::PP2]) => 'Pre-Primary',
            in_array($this, [self::Grade1, self::Grade2, self::Grade3]) => 'Lower Primary',
            in_array($this, [self::Grade4, self::Grade5, self::Grade6]) => 'Upper Primary',
            in_array($this, [self::Grade7, self::Grade8, self::Grade9]) => 'Junior Secondary',
            default => 'Senior Secondary',
        };
    }

    public function usesRubric(): bool
    {
        // Grades 1-6 use EE/ME/AE/BE; 7-12 use numeric marks
        return in_array($this, [
            self::PP1, self::PP2,
            self::Grade1, self::Grade2, self::Grade3,
            self::Grade4, self::Grade5, self::Grade6,
        ]);
    }
}
```

---

## 🗄️ Step 9 — Run Migrations & Seed

```bash
# Create the database first
mysql -u root -p -e "CREATE DATABASE cbc_school_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Run all migrations
php artisan migrate

# Run seeders
php artisan db:seed --class=RolesAndPermissionsSeeder
php artisan db:seed --class=GradeLevelsSeeder
php artisan db:seed --class=LearningAreasSeeder
php artisan db:seed --class=AdminUserSeeder
```

---

## 🔗 Step 10 — Route Groups Setup

In `routes/web.php`:

```php
<?php

use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', fn() => redirect()->route('login'));

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {

    // Admin / Principal / Deputy
    Route::middleware(['role:super-admin|principal|deputy-principal'])
        ->prefix('admin')
        ->name('admin.')
        ->group(base_path('routes/admin.php'));

    // Teachers / HOD
    Route::middleware(['role:teacher|class-teacher|hod'])
        ->prefix('teacher')
        ->name('teacher.')
        ->group(base_path('routes/teacher.php'));

    // Parents
    Route::middleware(['role:parent'])
        ->prefix('parent')
        ->name('parent.')
        ->group(base_path('routes/parent.php'));

    // Bursar
    Route::middleware(['role:bursar|super-admin|principal'])
        ->prefix('finance')
        ->name('finance.')
        ->group(base_path('routes/finance.php'));
});
```

---

## ▶️ Step 11 — Run the Application

```bash
# Terminal 1: Web server
php artisan serve

# Terminal 2: Queue worker (for SMS, emails, PDF generation)
php artisan queue:work --queue=high,default,low

# Terminal 3: Vite dev server (during development)
npm run dev

# Optional: Laravel Horizon (queue dashboard)
php artisan horizon
```

Visit: **http://localhost:8000**

Default login after seeding:
```
Email:    admin@school.ac.ke
Password: Admin@1234
```
> ⚠️ Change the password immediately on first login!

---

## 🧪 Step 12 — Run Tests

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter LearnerTest
php artisan test --filter AssessmentTest
php artisan test --filter MpesaPaymentTest

# With coverage report
php artisan test --coverage --min=80
```

---

## 📋 Quick Reference — Artisan Commands

```bash
# Clear all caches
php artisan optimize:clear

# Refresh migrations (development only!)
php artisan migrate:fresh --seed

# Create a new Livewire component
php artisan make:livewire Students/StudentList

# Create a new Model with migration + seeder + factory
php artisan make:model Learner -mfs

# Create a Job (for SMS/email queue)
php artisan make:job SendSmsJob

# Create a Service class
php artisan make:class Services/MpesaService

# View all routes
php artisan route:list --columns=method,uri,name,action

# Queue monitor
php artisan queue:monitor

# Schedule (run in production via cron)
php artisan schedule:run
```

---

## 🚢 Production Deployment Checklist

```bash
# 1. Set environment
APP_ENV=production
APP_DEBUG=false

# 2. Optimise
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
composer install --optimize-autoloader --no-dev
npm run build

# 3. Run migrations
php artisan migrate --force

# 4. Set up cron (add to crontab)
* * * * * cd /var/www/cbc-school && php artisan schedule:run >> /dev/null 2>&1

# 5. Supervisor config for queue worker
# /etc/supervisor/conf.d/cbc-school-worker.conf
[program:cbc-school-worker]
command=php /var/www/cbc-school/artisan queue:work redis --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=2
```

---

*Next steps: Database migrations → Models → Livewire components → PDF report cards*