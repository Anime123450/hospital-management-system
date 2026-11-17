# Hospital Management System

A student project from my internship: a web application to manage hospital operations — patients, doctors, appointments, bed allocation, and billing.

This is my first proper Laravel project. I started learning Laravel about a month ago so there's definitely stuff I'd do differently now, but I'm happy with how it turned out overall.

---

## What it does

- **Patients** — register patients, store their info (blood group, contact, address), view history
- **Doctors** — manage doctor profiles, specializations, consultation fees
- **Appointments** — schedule appointments between patients and doctors, track status (Pending / Confirmed / Completed / Cancelled)
- **Beds** — assign and track bed availability across wards (General, ICU, Private)
- **Billing** — generate invoices, track payments, see outstanding balances
- **Dashboard** — overview of everything with a chart showing appointment breakdown

Also has login/register and a dark mode toggle which I added because I personally use dark mode everywhere.

---

## Tech used

- **Laravel 13** (PHP framework — first time using it)
- **SQLite** for the database (easier for development)
- **Tailwind CSS** for styling
- **Chart.js** for the dashboard donut chart
- **Laravel Breeze** for authentication
- **Font Awesome** for icons

---

## How to run it locally

You'll need PHP 8.2+, Composer, and Node.js installed.

```bash
# Clone the repo
git clone https://github.com/Anime123450/hospital-management-system.git
cd hospital-management-system

# Install PHP dependencies
composer install

# Install JS dependencies
npm install

# Set up environment
cp .env.example .env
php artisan key:generate

# Run database migrations
php artisan migrate

# Build assets
npm run build

# Start the server
php artisan serve
```

Then open `http://localhost:8000`, register an account, and you're in.

---

## Folder structure (for reference)

```
app/
  Http/Controllers/     — PatientController, DoctorController, etc.
  Models/               — Patient, Doctor, Appointment, Bed, Bill
database/
  migrations/           — all the table definitions
resources/
  views/                — Blade templates (patients/, doctors/, appointments/, etc.)
routes/
  web.php               — all the routes
```

---

## What I learned building this

- How Laravel routing and MVC works
- Eloquent ORM — defining relationships between models (Patient hasMany Appointments, etc.)
- Blade templating and layouts
- Form validation in Laravel
- How migrations work and why they're useful
- Basic Git workflow (this was also my first time pushing a project to GitHub)

---

## What I'd improve with more time

- Add search and filter on more pages
- Export patients/bills to PDF
- Role-based access (admin vs. receptionist vs. doctor)
- Better mobile layout

---

## Screenshots

*(will add once the mentor reviews)*

---

Internship project — June 2026
