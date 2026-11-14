# VaccineCare -- Vaccination Management System

VaccineCare is a web-based Vaccination Management System that helps
organize child vaccination appointments and records. It provides
separate portals for Parents, Hospitals, and the Administrator.

## Features

### Parent Portal

-   View the parent dashboard.
-   Add children to the parent account.
-   Book vaccination appointments for children.
-   Track appointment statuses.
-   View children's vaccination records.

### Hospital Portal

-   View appointments assigned to the hospital.
-   Search and review appointment details.
-   Update appointment status according to the workflow.
-   Mark eligible appointments as completed.

### Admin Panel

-   View an overview of system activity.
-   Manage hospitals.
-   Manage vaccines.
-   Review appointment requests and approve or cancel them.

## Technology Stack

-   **Frontend:** HTML, CSS, Bootstrap, JavaScript
-   **Backend:** Core PHP
-   **Database:** MySQL
-   **Local development:** XAMPP
-   **Database access:** MySQLi

## Requirements

-   XAMPP with Apache and MySQL
-   A web browser
-   A code editor such as Visual Studio Code

## Installation and Setup

1.  Install and open XAMPP.

2.  Start **Apache** and **MySQL**.

3.  Copy or clone the project into the XAMPP `htdocs` directory.
    Example:

    `D:\xampp\htdocs\vaccination`

4.  Open phpMyAdmin at `http://localhost/phpmyadmin/`.

5.  Create a database named `vaccination`.

6.  Import the project's `.sql` database file into the `vaccination`
    database. If the SQL export is not included in the repository,
    obtain it from the project team.

7.  Check `config/db_connection.php` and make sure the host, database
    name, username, and password match your local MySQL configuration.
    The project uses the `$connect` connection variable.

8.  Open the project at `http://localhost/vaccination/`.

## User Roles and Workflow

1.  A Parent logs in, adds a child, and submits an appointment request.
2.  The Admin reviews the request and approves or cancels it.
3.  The Hospital views appointments assigned to it and updates their
    status.
4.  When vaccination is completed, the vaccination record is available
    in the Parent Portal.

## Demo Administrator Account

For local project demonstration only:

  Field      Demo value
  ---------- -------------------
  Email      `admin@gmail.com`
  Password   `admin321`

> **Security note:** These are demonstration credentials supplied for
> this project. Change the password before deploying the application or
> making the repository public. Do not use this password for any
> personal or production account. Use a unique administrator password in
> a real deployment and keep credentials out of source control.

## Project Structure

The application is organized by user role and shared configuration. The
exact files may vary by project version.

``` text
vaccination/
├── admin/
├── hospital/
├── parent/
├── config/
│   └── db_connection.php
├── index.php
└── ...
```

## Database Overview

The project uses a MySQL database named `vaccination`. Main entities
include:

-   `parents`
-   `hospital`
-   `child`
-   `vaccines`
-   `appointments`
-   `vaccination_record`
-   `admins`

Check the database export for the exact columns and relationships.

## Important Notes

-   Run the project through XAMPP; do not open PHP files directly from
    the file system.
-   Ensure Apache and MySQL are running.
-   Confirm database settings in `config/db_connection.php`.
-   Use test data for demonstrations.
-   Back up the database before making structural changes.



------------------------------------------------------------------------

**VaccineCare -- Vaccination Management System**
