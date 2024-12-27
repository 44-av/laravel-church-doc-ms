# Church Record Information System

## Overview
The Church Record Information System is a web application developed using Laravel, a PHP-based web framework. The system is designed to manage and organize church records efficiently, including member information, event schedules, financial contributions, and other essential church activities. This project aims to simplify administrative tasks, improve data accuracy, and enhance communication within the church community.

---

## Features

### Member Management
- Add, update, and delete member profiles.


### Document Requests
- Create and manage church events, documents, and services.
- Notify members about document transactions.

### Financial Contributions
- Track member donations and tithes.
- Generate reports on financial contributions.

### Reports
- Generate detailed reports on contributions

### User Roles and Permissions
- Admin users to manage all features.
- Standard users with limited access to specific functionalities.

---

## Tech Stack

- **Framework**: Laravel
- **Database**: MySQL
- **Frontend**: Blade Templating Engine (Laravel), Tailwind CSS

---

## Installation

### Prerequisites
1. PHP >= 8.0
2. Composer
3. MySQL
4. Node.js and npm (for frontend dependencies)

### Steps
1. Clone the repository:
   ```bash
   git clone https://github.com/your-repo/church-record-system.git
   cd church-record-system
   ```

2. Install dependencies:
   ```bash
   composer install
   npm install && npm run dev
   ```

3. Configure the `.env` file:
   ```env
   APP_NAME=ChurchRecordSystem
   APP_URL=http://localhost

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

4. Run migrations:
   ```bash
   php artisan migrate
   ```

5. Start the development server:
   ```bash
   php artisan serve
   ```

6. Access the application at [http://127.0.0.1:8000](http://127.0.0.1:8000).

---

## Usage

### Admin Login
The default admin credentials can be set during the seeding process or by manually updating the database after running migrations.

### Member Management
Admins can add, edit, and remove member records from the dashboard.

---

## Acknowledgments
Special thanks to the Laravel community for the robust framework and resources that made this project possible.

