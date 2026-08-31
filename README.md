# Lawfirm - Premier Advocate Discovery & Legal Appointment Booking Platform

A comprehensive, full-stack legal discovery and consultation scheduling web application built with **PHP, MySQL, HTML5, CSS3, JavaScript, and Bootstrap**.

Designed as a modern platform where clients can easily search, filter, and schedule appointments with verified legal advocates across Pakistan, while lawyers manage their practice profiles, consultation hours, and appointment requests.

---

## 🌟 Key Features

### 👤 1. Client / User Experience
* **Live Advocate Search & Multi-Criteria Filtering:** Search lawyers by name, law firm, alma mater, or filter by legal specialization (Corporate, Family, Criminal Defense, Taxation, Real Estate) and sort by experience or fee.
* **Rich Advocate Profiles:** View verified Bar Council IDs, academic degrees, law school background, years of litigation experience, consultation fees, and available days/hours.
* **Real-Time Appointment Scheduling:** Dynamic slot generation based on each lawyer's active working hours. Instant booking receipt with unique reference IDs (`#APT-XXXXX`).
* **Secure Client Authentication:** Password-protected accounts with Bcrypt hashing and auto-fill for instant bookings.

### ⚖️ 2. Lawyer / Advocate Portal
* **4-Step Interactive Onboarding Wizard:**
  1. *Personal & Chambers Info:* Contact details, office address, and secure password.
  2. *Professional Credentials:* Bar Council registration number, practice start year, law school degree, and languages.
  3. *Schedule & Consultation Fee:* Interactive working days selection, custom consultation hours, and fee in PKR.
  4. *Profile & Banner Media:* Instant image upload preview for avatar and chamber cover banner.
* **Profile Management:** Edit professional bio, consultation hours, and practice details at any time.

### 🛡️ 3. Administrative Management Portal
* **Real-Time Analytics Dashboard:** Instant counters for active advocates, registered clients, total consultations booked, and pending reviews.
* **Full CRUD Management:**
  * **Appointments:** View client details, assigned advocate, scheduled date/time slot, and update status (`Pending`, `Confirmed`, `Completed`, `Cancelled`).
  * **Advocate Directory:** View verified profiles, edit practice parameters, or delete inactive accounts.
  * **Client Management:** Manage registered client records safely.
  * **Practice Areas / Categories:** Create, edit, and organize legal specializations.
* **Strict Role-Based Security:** Protected admin routes with session authorization.

---

## 🔒 Security & Architecture Standards

* **Bcrypt Password Hashing:** 100% of user, lawyer, and admin passwords are secure using PHP `password_hash()` and `password_verify()`.
* **Zero SQL Injection (Prepared Statements):** All database transactions utilize parameterized prepared statements (`$stmt->bind_param()`).
* **Centralized Database Layer:** Unified `website/includes/db.php` connection helper with `utf8mb4` encoding.
* **Safe Asset Management:** Uploaded avatars and cover banners are sanitized with unique timestamped identifiers.
* **Valid HTML5 DOM Structure:** Clean, responsive semantic layout without nested duplicate tags or library conflicts.

---

## 🛠️ Technologies Used

* **Frontend:** HTML5, CSS3, JavaScript (ES6+), jQuery, Bootstrap, FontAwesome 6, Owl Carousel, Flexslider
* **Backend:** PHP 8.x (Object-Oriented & Procedural helpers)
* **Database:** MySQL 8.x / MariaDB
* **Architecture:** Modular MVC-inspired structure with centralized authentication and database services

---

## 🚀 Installation & Setup Guide

### Prerequisites
* Web Server (e.g. XAMPP, WAMP, Laragon, or Apache/Nginx + PHP 8+)
* MySQL / MariaDB Server
* phpMyAdmin or MySQL Workbench

### Installation Steps

1. **Clone the Repository:**
   ```bash
   git clone https://github.com/Zain1098/Find-a-Lawyer.git
   ```

2. **Place in Web Server Directory:**
   * For XAMPP: Copy to `C:/xampp/htdocs/Find-a-Lawyer-master`

3. **Import Database Schema:**
   * Open phpMyAdmin (`http://localhost/phpmyadmin`).
   * Create a new database named `e_project`.
   * Import the SQL schema file: [`database/e_project.sql`](database/e_project.sql).

4. **Launch the Application:**
   * **Public Portal:** `http://localhost/Find-a-Lawyer-master/website/index.php`
   * **Advocate Directory:** `http://localhost/Find-a-Lawyer-master/website/lawyer.php`
   * **Admin Dashboard:** `http://localhost/Find-a-Lawyer-master/Admin/index.php`

---

## 🔑 Demo Credentials

| Role | Email | Password | Access Area |
| :--- | :--- | :--- | :--- |
| **Administrator** | `za496694@gmail.com` | `1122` | `/Admin/index.php` |
| **Lawyer (Corporate)** | `za496694@gmail.com` | `1122` | `/website/profile.php?id=3` |
| **Lawyer (Family Law)** | `ammar@gmail.com` | `1122` | `/website/profile.php?id=1` |
| **Client / User** | `ammar.client@gmail.com` | `1122` | `/website/index.php` |

*(Note: Passwords are automatically hashed and validated via Bcrypt with automatic upgrade).*

---

## 📄 License
This project is licensed under the MIT License.

## 👨‍💻 Author
**Zain Ansari**
* Email: [za496694@gmail.com]
* Project: Lawyer Appointment Booking Platform for Portfolio
