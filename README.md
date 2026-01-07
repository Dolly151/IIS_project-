# IIS – Information Systems Project  
# WIS2 – Web Information System

**WIS2** is a web-based **school information system** developed as a semester project for the  
**IIS (Information Systems)** course at **FIT BUT (Faculty of Information Technology, Brno University of Technology)**.

The goal of the project was to **design and implement a functional academic information system**
for managing **courses, users, schedules, registrations, and student evaluation**.
The system supports **multiple user roles** with different permissions and responsibilities.

---

## Authors and Contributions

- **Róbert Páleş** (`xpalesr00`)  
  *Frontend* — profile management, login, overview, requests, rooms and room actions, user management, course detail service

- **Patrik Mokruša** (`xmokrup00`)  
  *Backend & Data Layer* — database abstraction layer, database initialization, authentication (login/logout/register),
  profile editing, role abstraction, request processing, course creation

- **Filip Doležal** (`xdolezf00`)  
  *Frontend* — student pages, teacher pages, terms management, course creation, teacher actions

---

## Deployment

The application is deployed on the **FIT BUT school server**:

```
http://www.stud.fit.vutbr.cz/~xpalesr00
```

---

## Technologies Used

- **PHP 8**
- **HTML5 / CSS**
- **MySQL**
- **Apache** (school server environment)

---

## Project Structure

The system is divided into **three logical layers**:

- **Data Access Layer (`data/`)**  
  Provides abstraction over direct database access and SQL queries.

- **Business Logic Layer (`services/`)**  
  Contains application logic, use-case handling, and permission control.

- **Presentation Layer (`pages/`)**  
  PHP-based pages responsible for rendering the user interface and handling user interaction.

Shared components (session initialization, menu, utilities) are included via common PHP files.

---

## Installation

The application was deployed on the **FIT BUT eva server** in the `WWW` directory
according to official university guidelines.

### Database Initialization

1. Create a **MySQL** database.
2. Run the initialization script:

```
mydp_creation_script.sql
```

> If necessary, adjust the **database name** directly inside the script.

---

## Running the Application Locally

### Requirements

To run the application locally, make sure you have the following installed:

- **PHP 8.0 or newer**
- **MySQL / MariaDB**
- **Apache, Nginx, or PHP built-in server**
- **Web browser**

---

### Setup Steps

#### 1. Clone or Download the Project

Clone the repository or extract the project files into a directory accessible by your web server:

```bash
git clone <repository-url>
cd <project-directory>
```

---

#### 2. Create the Database

Log in to **MySQL / MariaDB** and create a new database:

```sql
CREATE DATABASE wis2
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
```

> The database name can be changed if needed.

---

#### 3. Initialize the Database

Run the database initialization script:

```bash
mysql -u <user> -p wis2 < mydp_creation_script.sql
```

The script will:
- Create all required **tables**
- Insert **initial data** and **test users**

> If you use a different database name, update it inside  
> `mydp_creation_script.sql`.

---

#### 4. Configure Database Connection

Set the database credentials in the project configuration file  
(typically located in **`data/`** or **`common.php`**):

```php
$DB_HOST = 'localhost';
$DB_NAME = 'wis2';
$DB_USER = 'your_user';
$DB_PASS = 'your_password';
```

---

#### 5. Run the Application

##### Option A: PHP Built-in Server (recommended for local testing)

From the project root directory, run:

```bash
php -S localhost:8000
```

Then open your browser and navigate to:

```
http://localhost:8000
```

---

##### Option B: Apache or Nginx

Configure your web server's **document root** to point to the project directory  
and access the application via your local server URL.

---

## Test Users

The database is pre-filled with **test users** representing all system roles:

- **admin / admin** — Administrator  
- **teacher / teacher** — Teacher  
- **garant / garant** — Course guarantor  
- **student / student** — Student

