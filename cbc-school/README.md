# CBC School Management System

A comprehensive **Laravel 12** school management system built for Kenya's **Competency-Based Curriculum (CBC)**, with **KEMIS** integration, **M-Pesa** fee payments, SMS notifications, inventory management, and full CBC assessment tracking (EE/ME/AE/BE rubrics).

## Quick Start

```bash
composer install
cp .env.example .env
php artisan key:generate
# Configure .env with your DB, M-Pesa, Africa's Talking credentials
php artisan migrate --seed
npm install && npm run build
php artisan serve
```

See **SETUP.md** for the full installation guide.

## Default Logins (after seeding)

| Role        | Email                      | Password        |
|-------------|----------------------------|-----------------|
| super-admin | admin@school.ac.ke         | Admin@1234      |
| principal   | principal@school.ac.ke     | Principal@1234  |
| bursar      | bursar@school.ac.ke        | Bursar@1234     |

> ⚠️ Change all passwords on first login.

## Tech Stack

Laravel 12 · Livewire 3 · Tailwind CSS · MySQL 8 · Redis · M-Pesa Daraja · Africa's Talking SMS · Firebase FCM · KEMIS API

## License

MIT
