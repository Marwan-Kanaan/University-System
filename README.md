# University Management System

A PHP-based web application designed to simplify student and administrative interactions in a university setting. The system allows students to manage their profiles, view courses, and contact administrators while providing administrators with tools to manage students, courses, and system operations.

---

## Features

### **Student Features**
1. **Student Dashboard**:
   - View enrolled courses with detailed information.
   - Calendar with the current month displayed and the current day highlighted.
   - List of other students sharing similar courses.

2. **Profile Management**:
   - View personal information and profile photo.
   - Update profile details (name, email, phone, address, etc.).
   - Upload a profile photo that is securely stored in the database.
   - Download profile photo directly from the database.

3. **Admin Contact**:
   - Display a list of administrators with their name, email, and phone number.
   - Email administrators directly from the application.

4. **Authentication**:
   - Login functionality with session management.
   - Secure student access.

---

### **Admin Features**
1. **Admin Dashboard**:
   - View a comprehensive summary of all students and courses.
   - Monitor system usage and activity.

2. **Manage Students**:
   - Add, edit, and delete student records.
   - View detailed student profiles, including enrolled courses.

3. **Manage Courses**:
   - Add new courses, or update existing ones.
   - delete course.

4. **Manage Enrollments**:
   - Assign Student to a course, or update existing ones.
   - Delete Enrollment.

5. **System Security**:
   - Admin login with session-based access control.
   - Secure features accessible only to authenticated administrators.

---

## Technologies Used

- **Frontend**: 
  - HTML, CSS, JavaScript
  - Responsive design for mobile compatibility
- **Backend**: PHP
- **Database**: MySQL
- **Other**: Bootstrap (optional for advanced styling)

---
## Database Structure

### 1. Admins Table
| Column Name     | Data Type       | Description                      |
|-----------------|----------------|----------------------------------|
| `id`           | INT (11)       | Primary Key, Auto Increment      |
| `name`         | VARCHAR (255)  | Name of the admin                |
| `email`        | VARCHAR (255)  | Admin's email (unique)           |
| `password`     | VARCHAR (255)  | Encrypted admin password         |
| `address`      | TEXT           | Address of the admin             |
| `phone_number` | VARCHAR (15)   | Admin's contact number           |

---

### 2. Students Table
| Column Name       | Data Type       | Description                            |
|-------------------|----------------|----------------------------------------|
| `id`             | INT (11)       | Primary Key, Auto Increment            |
| `name`           | VARCHAR (255)  | Student's full name                    |
| `email`          | VARCHAR (255)  | Student's email (unique)               |
| `password`       | VARCHAR (255)  | Encrypted student password             |
| `address`        | TEXT           | Address of the student                 |
| `phone_number`   | VARCHAR (15)   | Contact number of the student          |
| `course`         | VARCHAR (100)  | Course name the student is enrolled in |
| `major`          | VARCHAR (100)  | Student's major or field of study      |
| `profile_photo`  | LONGBLOB       | Binary data for the student's photo    |

---

### 3. Courses Table
| Column Name         | Data Type       | Description                            |
|---------------------|----------------|----------------------------------------|
| `id`               | INT (11)       | Primary Key, Auto Increment            |
| `course_name`      | VARCHAR (255)  | Name of the course                     |
| `course_code`      | VARCHAR (20)   | Unique code for the course             |
| `course_description`| TEXT          | Description or details of the course   |

---

### 4. Enrollments Table
| Column Name       | Data Type       | Description                              |
|-------------------|----------------|------------------------------------------|
| `id`             | INT (11)       | Primary Key, Auto Increment              |
| `student_id`     | INT (11)       | Foreign Key referencing `students(id)`   |
| `course_id`      | INT (11)       | Foreign Key referencing `courses(id)`    |
| `enrollment_date`| TIMESTAMP      | Date and time of course enrollment       |

---

### Database Relationships
- The **Enrollments** table links `students` and `courses` via `student_id` and `course_id`.
- The **Admins** table is independent and used for administrative purposes.

---

## Setup Instructions

1. Import the `university_management` database schema into your MySQL server.
2. Configure the database connection in the `db.php` file located in the `includes/` folder.
3. Follow the folder structure and file hierarchy as per the code implementation.
4. All the Users password is : 123456.

---

## Installation Guide

### 1. Prerequisites
- PHP (>=7.4 recommended)
- MySQL database
- A web server (e.g., Apache, Nginx)
- A modern web browser

---

## The source code is available for download and use. University course ( WEB 2 ).

---

# Thank you ❤️
