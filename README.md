# Shining Light Christian College Inc.

A modern, responsive website for a faith-centered educational institution.

## Features

- **Homepage** – Hero section, CTAs (Enroll Now, Visit Us, Learn More), quick links, latest news
- **About Us** – History, mission, vision, faculty profiles (editable via admin)
- **Academics** – Programs, curriculum, academic calendar
- **Admissions** – Requirements, tuition, FAQs
- **Online Enrollment** – Secure form (student + parent info, document uploads), database storage, admin approval dashboard, auto-confirmation email, status tracking
- **Student Life** – Clubs, sports, gallery
- **News & Announcements** – Dynamic CMS (create/edit/publish)
- **Contact** – Form + Google Maps embed
- **Admin Panel** – Content, enrollments, faculty, gallery, pages, settings, contacts; role-based access (admin/editor/viewer)
- **Design** – Forest green, gold, and warm neutrals; photography-led page headers; responsive layouts, accessible navigation, animated cards, and hover-based link states; Bible verse in footer; SEO-friendly metadata

## Tech Stack

This project is built with a lightweight LAMP-style stack:

- PHP 7.4+ – server-side logic, admin authentication, form handling, and database access using PDO
- MySQL 5.7+ / MariaDB – relational database for pages, news, faculty, gallery, enrollment records, and contact submissions
- Apache + XAMPP – local web server environment for development and deployment
- HTML5, CSS3, and JavaScript – responsive page structure, styling, and client-side interactivity
- Composer – dependency management and autoloading support

## Requirements

- PHP 7.4+ (with PDO MySQL)
- MySQL 5.7+ / MariaDB
- Apache (XAMPP) or similar

## Installation

1. Place the project in your web root (e.g. `C:\xampp\htdocs\ShiningLight`).
2. Start Apache and MySQL (XAMPP Control Panel).
3. Open **http://localhost/ShiningLight/install/** in a browser.
4. Enter MySQL credentials (default: root, no password) and run the installer. This creates the database `shining_light_db`, tables, default admin, and upload folders.
5. **Default admin:** username `admin`, password `admin123`. Change this after first login.
6. (Optional) Restrict or remove the `install` folder after setup.

## Configuration

- **config/database.php** – DB host/name/user/pass, `ADMIN_EMAIL`, upload path and max file size. `SITE_URL` is inferred from the current request; set the `SHINING_LIGHT_SITE_URL` environment variable to the public site URL (without a trailing slash) when deploying behind a proxy or when automatic detection is unsuitable.
- **Admin → Settings** – Address, phone, tuition text, admissions requirements, student life text, Google Maps embed URL, footer Bible verses.

## Google Maps

In Admin → Settings, paste either the **embed URL** (e.g. `https://www.google.com/maps/embed?pb=...`) or the full iframe `src` value from Google Maps (Share → Embed a map).

## Optional Enhancements

- **Parent/student portals** – Add separate login and dashboards for parents/students (e.g. view enrollment status, grades).
- **Online payments** – Integrate a payment gateway (e.g. PayPal, Stripe) for tuition/fees.
- **Email** – For production, use SMTP (e.g. PHPMailer) instead of `mail()` for signup and approval emails.

## Structure

- `/` – Public pages (index, about, academics, admissions, enroll, enroll-status, student-life, news, news-single, contact)
- `/admin/` – Admin panel (login, dashboard, enrollments, news, faculty, gallery, pages, settings, contacts)
- `/assets/css/` – style.css (public), admin.css
- `/config/` – database.php
- `/includes/` – auth.php, functions.php, header.php, footer.php
- `/uploads/` – enrollment docs, news images, faculty photos, gallery (with .htaccess to block script execution)
- `/install/` – setup SQL and one-time web installer

## Security

- Admin routes use session-based auth and role checks.
- File uploads are restricted by extension and size; uploads directory denies PHP execution.
- Enrollment form uses CSRF token; inputs are escaped for output.
