## Project Setup Guide

### Prerequisites
- PHP 8.X
- Composer
- MariaDB 10.4.X

### Installation
1. Clone the repository
```bash
git clone https://github.com/iHasanMasud/user-management-system.git

```
2. Install dependencies
```bash
composer install
```
3. Create a new database
```bash
mysql -u username -p database_name < user_management_system.sql
```
4. update the config.php file with your database credentials


5. Run the application at localhost/your-folder-name

6. Run tests
```bash
./vendor/bin/phpunit --bootstrap vendor/autoload.php tests
```

### Login Credentials
- Admin
    - Email: admin@example.com
    - Password: Admin_0101
- User
    - Email: user@example.com
    - Password: User_0101

## Features
- User Management
- User Listings
- Security
- User Interface
- Data Persistence
- Error Handling
- Testing