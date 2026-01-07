# IIS - Information systems project 
# WIS2 – Web Information System

WIS2 is a web-based **school information system** developed as a semester project assignment for the **IIS (Information Systems)** course at FIT BUT.

The goal of the project was to design and implement a functional academic information system for managing courses, users, schedules, registrations, and student evaluation. The system supports multiple user roles with different permissions.

---

## Authors and Contributions

- **Róbert Páleş** (xpalesr00) – Frontend (profile, login, overview, requests, rooms & actions, users, course detail service)
- **Patrik Mokruša** (xmokrup00) – Data Layer + Backend (DB abstraction, DB init, auth, profile edit, roles, requests processing, course creation)
- **Filip Doležal** (xdolezf00) – Frontend (student pages, teacher pages, terms, course creation, teacher actions)

---

## Deployment

The application is deployed on the FIT BUT school server:

http://www.stud.fit.vutbr.cz/~xpalesr00

---

## Technologies Used

- PHP 8
- HTML5 / CSS
- MySQL
- Apache (school server environment)

---

## Project Structure

The system is divided into three logical layers:

- **Data Access Layer (`data/`)**  
  Provides abstraction over database access and SQL queries.

- **Business Logic Layer (`services/`)**  
  Contains application logic and permission handling.

- **Presentation Layer (`pages/`)**  
  PHP-based pages responsible for rendering the user interface and handling user interaction.

Shared components and utilities are included via common PHP files.

---

## Installation

The application was deployed on the FIT BUT **eva** server in the `WWW` directory according to official university guidelines.

To initialize the database:

1. Create a MySQL database.
2. Run the `mydp_creation_script.sql` script (adjust the database name inside the script if necessary).

---

## Running the Application Locally

### Requirements

- PHP 8.0 or newer
- MySQL / MariaDB
- Apache, Nginx, or PHP built-in server
- Web browser
