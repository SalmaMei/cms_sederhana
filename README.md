# CMS Sederhana

A simple Content Management System built with PHP and AdminLTE.

## Features

- User authentication
- Article management
- Category management
- User management
- Responsive admin dashboard
- Clean and modern UI using AdminLTE

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)

## Installation

1. Clone or download this repository to your web server directory
2. Create a new MySQL database
3. Import the `database.sql` file to create the necessary tables and initial data
4. Configure the database connection in `config/database.php`
5. Access the CMS through your web browser

## Default Login

- Username: admin
- Password: admin123

## Directory Structure

```
cms_sederhana/
├── config/
│   └── database.php
├── includes/
│   ├── header.php
│   ├── sidebar.php
│   └── footer.php
├── pages/
│   ├── dashboard.php
│   ├── articles.php
│   ├── categories.php
│   └── users.php
├── index.php
├── logout.php
└── database.sql
```

## Security

- Change the default admin password after first login
- Keep your PHP and MySQL versions up to date
- Use HTTPS in production environment

## License

This project is open-source and available under the MIT License. 