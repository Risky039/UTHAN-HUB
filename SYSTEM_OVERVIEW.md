# School Management System Overview

## Introduction
This is a multi-tenant School Management System API built with Laravel. It supports multiple schools, each with their own subdomain, data, and configuration.

## Key Features

### 1. Multi-Tenancy
*   **Subdomains:** Each school is assigned a unique subdomain (e.g., `school1.utanhub.test`).
*   **Data Isolation:** Data is logically separated using `tenant_id` and `school_id`.

### 2. Academic Tracking
*   **Sessions & Terms:** Manage academic sessions and terms.
*   **Classes & Subjects:** Structure classes and assign subjects.
*   **Results:** Teachers can upload results.
*   **Attendance:** Track student attendance.
*   **Result Release:** Admins can control when results are released.

### 3. Financial Management (New)
*   **Term Fees:** Admins can set a specific fee amount per term for the school.
*   **Payments:** Track student payments for each term.
*   **Portal Access Control:** Students must pay the term fee to access the portal (e.g., view results). This is enforced via the `CheckPaymentMiddleware`.

### 4. Role-Based Access Control (RBAC)
*   **Roles:** Admin, Teacher, Student, Accountant.
*   **Permissions:** Granular permissions for managing users, fees, results, etc.
*   **Dynamic Assignment:** Admins can manage roles and permissions via the API (`/roles` endpoint).
*   **Initial Setup:** `RolePermissionSeeder` sets up default roles and permissions.

### 5. System Automation
*   **System Reset Countdown:** After results are released, the system calculates a countdown to the reset date (start of new term/session).

## API Endpoints

### Authentication
*   `POST /auth/admin/login`: Admin login.
*   `POST /auth/student/register`: Student registration.
*   `POST /auth/teacher/register`: Teacher registration.

### School Management
*   `POST /schools`: Register a new school (and tenant).
*   `PUT /schools/{id}/settings`: Update school settings (e.g., term fee).

### Academic
*   `GET /academic-session`: List academic sessions.
*   `POST /academic-session`: Create academic session.
*   `GET /terms`: List terms.
*   `POST /terms`: Create term.
*   `POST /terms/{term}/release-results`: Release results and set reset date.
*   `GET /terms/{term}/countdown`: Get countdown to system reset.

### Payments
*   `POST /payments`: Record a payment (Admin/Accountant).
*   `GET /payments/status`: Check payment status (Student).

### Role Management
*   `GET /roles`: List roles.
*   `POST /roles`: Create a new role.
*   `PUT /roles/{role}`: Update a role.

## Database Schema Enhancements

### New Tables
*   `payments`: Tracks student payments.
    *   `student_id`, `term_id`, `school_id`, `amount`, `status`, `transaction_reference`.

### Modified Tables
*   `schools`: Added `term_fee` column.
*   `terms`: Added `results_released_at` and `reset_date` columns.
*   `roles`: Made `teacher_id` nullable to support generic roles like "Accountant".

## Setup Instructions

1.  **Install Dependencies:**
    ```bash
    composer install
    npm install
    ```

2.  **Run Migrations:**
    ```bash
    php artisan migrate
    ```

3.  **Seed Database (Roles/Permissions):**
    ```bash
    php artisan db:seed --class=RolePermissionSeeder
    ```

4.  **Serve Application:**
    ```bash
    npm run dev
    # or
    php artisan serve
    ```

## Usage Flow

1.  **School Registration:** A new school registers, creating a tenant and admin user.
2.  **Setup:** Admin logs in, sets up academic session, term, and **term fee**.
3.  **Role Management:** Admin creates an "Accountant" role and assigns "manage-fees" permission.
4.  **Student Enrollment:** Students register or are added by admin.
5.  **Payment:** Students pay fees. Accountant records payment via `/payments` endpoint.
6.  **Access:** Student logs in. If fees are paid, they can access portal features. If not, access is denied.
7.  **End of Term:** Teachers upload results.
8.  **Release:** Admin releases results via `/terms/{term}/release-results`.
9.  **Countdown:** System shows countdown to reset date.
