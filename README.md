# User Management System

## Overview
This User Management System is designed for managing Warehouse Admins and their products. It allows users to register, activate their accounts, log in, and manage their profiles and products. The system includes features for password recovery and account activation via email.

## Features
- **User Registration**: Users can register as Warehouse Admins using their email as the username. The system checks for existing registrations.
- **Email Activation**: Upon successful registration, an activation link is sent to the user's email. Clicking the link activates the user's account.
- **Login**: Users can log in to access the Warehouse Admin Dashboard.
- **Dashboard**: After logging in, users can manage products and their profiles.
- **CRUD Operations**: Users can create, read, update, and delete product data.
- **Profile Management**: Users can view and update their profile information.
- **Change Password**: Users can change their passwords securely.
- **Forgot Password**: Users can request a password reset link via email.
- **Reset Password**: Users can set a new password after verifying their identity through a reset link.

## Project Structure
```
user-management-system
├── index.php
├── login.php
├── register.php
├── activate.php
├── forgot_password.php
├── reset_password.php
├── dashboard.php
├── profile.php
├── change_password.php
├── products.php
├── add_product.php
├── edit_product.php
├── delete_product.php
├── includes
│   ├── db.php
│   └── functions.php
├── css
│   └── style.css
├── js
│   └── script.js
├── create_tables.php
└── README.md
```

## Database Tables
1. **USERS**: 
   - `id`: INT, primary key, auto-increment
   - `email`: VARCHAR, unique, not null
   - `password`: VARCHAR, not null
   - `fullname`: VARCHAR, not null
   - `status`: ENUM('ACTIVE', 'INACTIVE'), default 'INACTIVE'
   - `reg_date`: TIMESTAMP, default current timestamp

2. **PRODUCTS**: 
   - `id`: INT, primary key, auto-increment
   - `name`: VARCHAR, not null
   - `description`: TEXT
   - `price`: DECIMAL(10, 2), not null
   - `quantity`: INT, not null
   - `creation_date`: TIMESTAMP, default current timestamp

## Setup Instructions
1. Clone the repository or download the project files.
2. Set up a local server environment using XAMPP or similar.
3. Import the SQL commands from `create_tables.php` to create the necessary database tables.
4. Configure the database connection in `includes/db.php`.
5. Access the application through your web browser at `http://localhost/user-management-system/index.php`.

## Technologies Used
- PHP
- MySQL
- HTML/CSS
- JavaScript

## License
This project is open-source and available for modification and distribution.