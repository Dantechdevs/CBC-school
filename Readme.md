# 🏫 CBC School Management System

> A comprehensive Laravel-based School Management System aligned with Kenya's **Competency-Based Curriculum (CBC)** and integrated with **KEMIS** (Kenya Education Management Information System).

---

## 📋 Table of Contents

- [Overview](#overview)
- [CBC Grade Structure](#cbc-grade-structure)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Modules](#modules)
- [User Roles](#user-roles)
- [API Integrations](#api-integrations)
- [Database](#database)
- [Testing](#testing)
- [Contributing](#contributing)
- [License](#license)

---

## Overview

The **CBC School Management System** is a full-featured, web-based platform built with Laravel 11 to help Kenyan schools manage their operations in alignment with the Competency-Based Curriculum (CBC). It replaces traditional mark-based tracking with CBC's competency rubric system (EE/ME/AE/BE), supports learner portfolios, integrates M-Pesa payments, and syncs data with the government's KEMIS platform.

```
Pre-Primary → Lower Primary → Upper Primary → Junior Secondary → Senior Secondary
  PP1–PP2        Gr 1–3          Gr 4–6           Gr 7–9             Gr 10–12
```

---

## CBC Grade Structure

| Level | Grades | Assessment Style |
|---|---|---|
| Pre-Primary | PP1 – PP2 | Observational / Portfolio |
| Lower Primary | Grade 1 – 3 | EE / ME / AE / BE Rubrics |
| Upper Primary | Grade 4 – 6 | EE / ME / AE / BE + KPSEA (Gr 6) |
| Junior Secondary | Grade 7 – 9 | Numeric marks + Rubrics |
| Senior Secondary | Grade 10 – 12 | Pathways-based + KCSE (Gr 12) |

### Assessment Rubric (Primary Levels)

| Code | Descriptor | Meaning |
|---|---|---|
| **EE** | Exceeds Expectation | Learner surpasses expected competency |
| **ME** | Meets Expectation | Learner fully demonstrates competency |
| **AE** | Approaches Expectation | Learner is progressing toward competency |
| **BE** | Below Expectation | Learner needs additional support |

---

## Features

### 🎓 Student Management
- Learner registration and enrollment (PP1 → Grade 12)
- KEMIS learner number synchronization
- Learner profiles with photo, guardian contacts, medical notes
- Class and stream assignment per term
- Transfer in/out management with history
- Alumni records and graduation tracking

### 📋 CBC Assessment Engine
- EE / ME / AE / BE rubric entry per strand and sub-strand
- Formative (continuous) and summative assessment tracking
- 40% continuous + 60% end-of-term grade weighting
- Competency progress tracking per learner per learning area
- Grade 7–9 numeric marks support (Junior Secondary)
- Bulk assessment entry for class teachers
- Historical competency trend per learner

### 📄 Report Cards & Transcripts
- CBC-format report card generation (PDF)
- Competency descriptors per learning area
- Teacher and principal remarks
- Term-by-term history and archives
- Parent digital delivery via email or SMS link
- Printable A4 format reports

### 📚 Curriculum & Lesson Planner
- Learning areas mapped per grade level
- Strands, sub-strands, and specific learning outcomes (SLOs)
- Lesson plan creation and HOD/principal approval workflow
- Scheme of work management per term
- CBC learning resource and notes upload (PDF, video, documents)
- Resources organised by grade, learning area, and strand

### 📝 Exams & Tests Management
- Create exams per learning area, grade, and term
- Question bank (MCQ, short answer, essay types)
- Exam timetable builder and invigilation schedule
- Mark entry and auto-calculation
- KNEC/KPSEA alignment for Grade 6
- Summative results archiving per term and year

### 📓 Learning Notes & Resources
- Teacher uploads notes per learning area and grade
- Learner portal to view and download notes
- Organised by strand and sub-strand
- Supports PDFs, video links, and images
- Downloadable offline packs for low-connectivity areas
- Parent visibility into learning materials

### 🔔 Notifications & Communication
- SMS notifications via Africa's Talking API
- Email notifications via Mailgun or SMTP
- In-app notifications (Laravel + Livewire)
- Push notifications via Firebase Cloud Messaging (FCM)
- School-wide circular and notice board
- Parent-teacher direct messaging
- Automated alerts: fees due, report card ready, exam schedule, absenteeism
- Bulk SMS targeting (e.g. all Grade 4 parents, all boarding parents)

### 💰 Fees & Payments
- Fee structure setup per term, grade, and day/boarding category
- Individual fee invoicing per learner
- **M-Pesa integration** (Safaricom Daraja API — STK Push & C2B)
- Bank payment receipting and manual entry
- Bursary and scholarship management
- Fee balance and arrears tracking with aging report
- PDF payment receipts and statements
- Finance reports: collected, pending, waived
- Automated SMS reminders for fee arrears

### 📦 Inventory Management
- School assets register (furniture, electronics, equipment)
- Textbook and CBC learning kit inventory per grade
- Issue and return tracking (books assigned to learners)
- Stock levels with low-stock alerts
- Procurement requests and Local Purchase Order (LPO) management
- Science lab and equipment register
- Stationery store management
- Disposal, loss, and damage recording

### 👩‍🏫 Staff & HR Management
- Teacher profiles with TSC number, qualifications, and photo
- Learning area and class assignments
- Leave management (annual, sick, maternity, paternity, compassionate)
- Staff daily attendance tracking
- Non-teaching staff management
- Payroll summary (basic, allowances, PAYE, NHIF, NSSF, net)
- Professional development and CPD records

### ⏰ Timetable & Scheduling
- Auto-generate and manual timetable builder
- Learning areas, streams, teacher, and room assignment
- Conflict detection (teacher double-booking prevention)
- Substitute teacher assignment
- CBC integrated and thematic day scheduling support
- Published timetables visible on teacher and learner portals

### 📊 Analytics & Reporting
- School performance dashboard (principal/admin view)
- Per-class and per-learner competency trend charts
- Fee collection analytics and projections
- Attendance heatmaps and absenteeism reports
- KEMIS-ready data export (CSV/JSON)
- Ministry of Education compliance report generation
- Board of Governors (BOG) summary reports

### 🏫 Parent & Guardian Portal
- View child's competency progress per learning area
- Download term report cards (PDF)
- Pay fees directly via M-Pesa (STK Push)
- Receive and view all school notifications
- Access learning notes and materials
- Direct messaging with class teacher

### 🌐 KEMIS Integration
- Learner data synchronization to KEMIS
- KEMIS learner UPI number lookup and linking
- School registration data pull
- Capitation eligibility data export
- Grade 6 KPSEA candidate registration support
- Bulk learner data upload to KEMIS


See **SETUP.md** for the full installation guide.

## Default Logins (after seeding)

| Role        | Email                      | Password        |
|-------------|----------------------------|-----------------|
| super-admin | admin@school.ac.ke         | Admin@1234      |
| principal   | principal@school.ac.ke     | Principal@1234  |
| bursar      | bursar@school.ac.ke        | Bursar@1234     |

> ⚠️ Change all passwords on first login.


---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend Framework | Laravel 11 |
| Frontend / UI | Livewire 3 + Alpine.js |
| CSS Framework | Tailwind CSS |
| Authentication | Laravel Breeze / Jetstream |
| Authorization / Roles | Spatie Laravel Permission |
| PDF Generation | DomPDF (barryvdh/laravel-dompdf) |
| SMS | Africa's Talking PHP SDK |
| M-Pesa Payments | Safaricom Daraja API |
| Push Notifications | Firebase Cloud Messaging (FCM) |
| Email | Laravel Mailgun / SMTP |
| File Storage | Laravel Storage (S3 / local) |
| Background Jobs | Laravel Horizon + Redis |
| Database | MySQL 8.0 |
| Search | Laravel Scout (optional) |
| Charts | ApexCharts / Chart.js |
| Testing | PHPUnit + Pest |

---

## Requirements

- PHP >= 8.2
- Composer 2.x
- Node.js >= 18.x & NPM
- MySQL 8.0+
- Redis (for queues and caching)
- Africa's Talking account (SMS)
- Safaricom Daraja API credentials (M-Pesa)
- Firebase project (push notifications)

---

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/your-org/cbc-school-management.git
cd cbc-school-management
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Install Node dependencies

```bash
npm install && npm run build
```

### 4. Environment setup

```bash
cp .env.example .env
php artisan key:generate
```

### 5. Configure your `.env` file

```env
APP_NAME="CBC School Management System"
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cbc_school
DB_USERNAME=root
DB_PASSWORD=

# Africa's Talking (SMS)
AT_API_KEY=your_api_key
AT_USERNAME=your_username
AT_SENDER_ID=SCHOOL

# Safaricom Daraja (M-Pesa)
MPESA_CONSUMER_KEY=your_consumer_key
MPESA_CONSUMER_SECRET=your_consumer_secret
MPESA_SHORTCODE=174379
MPESA_PASSKEY=your_passkey
MPESA_ENV=sandbox  # Change to "production" when live

# Firebase (Push Notifications)
FIREBASE_SERVER_KEY=your_firebase_server_key

# Mail
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your_domain
MAILGUN_SECRET=your_secret

# Redis (Queues)
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
```

### 6. Run migrations and seeders

```bash
php artisan migrate --seed
```

### 7. Link storage

```bash
php artisan storage:link
```

### 8. Start the development server

```bash
php artisan serve
php artisan queue:work   # In a separate terminal
```

### 9. (Optional) Run with Laravel Horizon

```bash
php artisan horizon
```

---

## Configuration

### Roles & Permissions

Roles are seeded automatically. Default roles:

| Role | Access Level |
|---|---|
| `super-admin` | Full system access |
| `principal` | School-wide management |
| `deputy-principal` | Academics and discipline |
| `hod` | Department / learning area oversight |
| `class-teacher` | Class management + assessment entry |
| `teacher` | Assessment entry + notes upload |
| `bursar` | Fees, payments, finance reports |
| `librarian` | Library and resource management |
| `storekeeper` | Inventory management |
| `parent` | Child progress, fees, notifications |
| `learner` | Notes, timetable, results view |

### Default Admin Login (after seeding)

```
Email:    admin@school.ac.ke
Password: password
```

> ⚠️ Change the default password immediately after first login.

---

## Modules

```
app/
├── Modules/
│   ├── Students/
│   ├── Assessment/
│   ├── Curriculum/
│   ├── ReportCards/
│   ├── Exams/
│   ├── Notes/
│   ├── Fees/
│   ├── Inventory/
│   ├── Staff/
│   ├── Timetable/
│   ├── Notifications/
│   ├── Parents/
│   ├── Analytics/
│   └── KEMIS/
```

---

## User Roles

### Access Matrix (Summary)

| Feature | Super Admin | Principal | HOD | Teacher | Bursar | Parent | Learner |
|---|:---:|:---:|:---:|:---:|:---:|:---:|:---:|
| Manage students | ✅ | ✅ | ✅ | 👁️ | 👁️ | ❌ | ❌ |
| Enter assessments | ✅ | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| View report cards | ✅ | ✅ | ✅ | ✅ | ❌ | ✅ | ✅ |
| Manage fees | ✅ | ✅ | ❌ | ❌ | ✅ | 👁️ | ❌ |
| Pay fees | ❌ | ❌ | ❌ | ❌ | ❌ | ✅ | ❌ |
| Upload notes | ✅ | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| View notes | ✅ | ✅ | ✅ | ✅ | ❌ | ✅ | ✅ |
| Manage inventory | ✅ | ✅ | ❌ | ❌ | ✅ | ❌ | ❌ |
| KEMIS sync | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| Analytics | ✅ | ✅ | ✅ | ❌ | ✅ | ❌ | ❌ |

> ✅ Full access &nbsp;|&nbsp; 👁️ View only &nbsp;|&nbsp; ❌ No access

---

## API Integrations

### M-Pesa (Safaricom Daraja)
- **STK Push** — Parent initiates payment from their phone
- **C2B** — School paybill receives payments automatically
- Callbacks update fee ledger in real time
- Endpoint: `POST /api/mpesa/callback`

### Africa's Talking (SMS)
- Bulk SMS for school announcements
- Transactional SMS (fee receipts, report card alerts)
- Delivery reports tracked in database

### Firebase Cloud Messaging (FCM)
- Push notifications for the school mobile app
- Targeted by role, grade, or individual user

### KEMIS (Kenya Education Management Information System)
- Learner UPI registration and lookup
- Data export compatible with KEMIS import format
- Scheduled sync jobs via Laravel scheduler
- Full transition from NEMIS to KEMIS (rollout: July 2025)

---

## Database

Core tables overview:

```
learners                  — Learner profiles and enrollment
guardians                 — Parent/guardian records
classes                   — Grade, stream, academic year
learning_areas            — CBC learning areas per grade
strands                   — Strands per learning area
sub_strands               — Sub-strands per strand
assessments               — EE/ME/AE/BE entries per learner
exam_results              — Numeric marks for Jr/Sr Secondary
fee_structures            — Fee setup per grade/term
fee_invoices              — Per-learner invoices
fee_payments              — Payment records (M-Pesa, bank)
inventory_items           — Assets, books, equipment
inventory_transactions    — Issue, return, disposal events
staff                     — Teacher and non-teaching staff
timetable_slots           — Scheduled learning periods
notifications_log         — SMS and email delivery log
kemis_sync_log            — KEMIS data sync history
```

Generate ERD:
```bash
php artisan erd:generate
```

---

## Testing

```bash
# Run all tests
php artisan test

# Run specific module tests
php artisan test --filter AssessmentTest
php artisan test --filter FeesPaymentTest

# Run with coverage
php artisan test --coverage
```

---

## Roadmap

- [x] Student management & KEMIS sync
- [x] CBC assessment engine (EE/ME/AE/BE)
- [x] M-Pesa fee payments (Daraja API)
- [x] SMS & push notifications
- [x] Inventory management
- [x] Learning notes & resources module
- [ ] Mobile app (Flutter — Android & iOS)
- [ ] Offline mode for low-connectivity schools
- [ ] AI-powered learner progress recommendations
- [ ] BI dashboard for county education officers
- [ ] Multi-school / county rollout support

---

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/your-feature`)
3. Commit your changes (`git commit -m 'Add: your feature description'`)
4. Push to the branch (`git push origin feature/your-feature`)
5. Open a Pull Request

Please follow [PSR-12](https://www.php-fig.org/psr/psr-12/) coding standards and write tests for new features.

---

## License

This project is licensed under the [MIT License](LICENSE).

---

## Acknowledgements

- [Kenya Ministry of Education](https://education.go.ke) — CBC curriculum framework
- [KEMIS](https://kemis.education.go.ke) — Kenya Education Management Information System
- [Safaricom Daraja](https://developer.safaricom.co.ke) — M-Pesa payment API
- [Africa's Talking](https://africastalking.com) — SMS gateway
- [Laravel](https://laravel.com) — The PHP framework for web artisans

---

*Built with ❤️ for Kenyan schools.*