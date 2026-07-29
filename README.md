# 🛕 Temple ERP - Integrated Temple Administration & Devotee Services Portal

Temple ERP is a comprehensive web-based Enterprise Resource Planning (ERP) system developed to digitize and automate temple management. The application simplifies temple administration by providing dedicated portals for Devotees, Priests, Staff, Trustees, Accountants, and Administrators.

---

## 🚀 Features

### 👤 Authentication
- Multi-role Login System
- Email OTP Verification
- Forgot Password with OTP
- Secure Authentication
- Role-Based Access Control

### 🙏 Devotee Module
- Online Registration
- Profile Management
- Pooja Booking
- E-Hundi Donations
- Donation History
- Event Information
- Chat Support

### 🛕 Priest Module
- Assigned Poojas
- Daily Schedule
- Leave Management
- Profile Management

### 👨‍💼 Staff Module
- Attendance Management
- Shift Tracking
- Support Chat
- Dashboard

### 💰 Accountant Module
- Donation Records
- Payroll Management
- Financial Reports
- Expense Tracking

### 👨‍⚖️ Trustee Module
- Temple Reports
- Financial Overview
- Event Monitoring

### 👨‍💻 Admin Module
- Dashboard
- User Management
- Priest Management
- Staff Management
- Trustee Management
- Accountant Management
- Devotee Verification
- Pooja Management
- Event Management
- Donation Management
- Reports
- System Settings

---

# 🏗️ Technology Stack

| Technology | Version |
|------------|---------|
| Laravel | 11.x |
| PHP | 8.2+ |
| MySQL | 8.0 |
| SQLite | Testing |
| HTML5 | ✓ |
| CSS3 | ✓ |
| Bootstrap 5 | ✓ |
| JavaScript | ✓ |
| jQuery | ✓ |
| AJAX | ✓ |
| Blade Template Engine | ✓ |

---

# 📂 Project Structure

```
TempleERP/
│
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
├── resources/
├── routes/
├── storage/
├── tests/
├── vendor/
└── artisan
```

---

# ⚙️ Installation

## Clone Repository

```bash
git clone https://github.com/your-username/TempleERP.git
```

## Move into Project

```bash
cd TempleERP
```

## Install Dependencies

```bash
composer install
```

## Copy Environment File

```bash
cp .env.example .env
```

## Generate Application Key

```bash
php artisan key:generate
```

## Configure Database

Update the `.env` file:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=temple_erp
DB_USERNAME=root
DB_PASSWORD=
```

## Run Migrations

```bash
php artisan migrate
```

## Seed Database (Optional)

```bash
php artisan db:seed
```

## Start Server

```bash
php artisan serve
```

Application URL

```
http://127.0.0.1:8000
```

---

# 🔒 Security Features

- Role-Based Authentication
- CSRF Protection
- SQL Injection Prevention
- XSS Protection
- Password Hashing
- Session Management
- OTP Verification
- Email Verification

---

# 📸 Screenshots




---

# 👨‍💻 Developed By

**Rohan**

B.Tech Computer Science Engineering

MIT Manipal

---

# 📄 License

This project is developed for educational and academic purposes.

---

