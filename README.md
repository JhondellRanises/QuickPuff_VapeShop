# Quick Puff Vape Shop - Inventory & Sales Management System

A modern web-based inventory and sales management system for vape shops, built with CodeIgniter 4 and Bootstrap 5.

## 🚀 Sprint 1 Features

### ✅ Completed Features

- **Secure Admin Login**
  - Password hashing with `password_hash()`
  - Session-based authentication
  - Role-based access control (Admin/Staff)
  - Logout functionality

- **Staff Management (Admin Only)**
  - Create, edit, activate/deactivate staff accounts
  - Role assignment (Admin/Staff)
  - User validation and error handling
  - Soft delete functionality

- **POS Product Selection**
  - Search and filter products
  - Add products to cart with stock validation
  - Real-time cart updates
  - Quantity adjustment
  - Automatic total calculation
  - Session-based cart storage

## 🛠️ Tech Stack

- **Backend**: CodeIgniter 4 (PHP)
- **Frontend**: HTML5, CSS3, JavaScript
- **UI Framework**: Bootstrap 5
- **Database**: MySQL
- **Icons**: Font Awesome 6

## 📋 System Requirements

- PHP 8.1 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Composer (for dependency management)

## 🚀 Installation Guide

### 1. Clone/Download the Project

```bash
# If using git
git clone <repository-url>
cd QuickPuff

# Or download and extract the ZIP file
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Database Setup

1. Create a new database in MySQL:
   ```sql
   CREATE DATABASE quickpuff_db;
   ```

2. Update database configuration in `app/Config/Database.php`:
   ```php
   public $default = [
       'DSN'      => 'mysql:host=localhost;dbname=quickpuff_db',
       'hostname' => 'localhost',
       'username' => 'your_mysql_username',
       'password' => 'your_mysql_password',
       'database' => 'quickpuff_db',
       // ... other settings
   ];
   ```

### 4. Run Database Migrations

```bash
# From the project root directory
php spark migrate
```

### 5. Seed the Database

```bash
# Run seeders to create default admin and sample products
php spark db:seed UserSeeder
php spark db:seed ProductSeeder
```

### 6. Configure Web Server

#### Apache (.htaccess)

Ensure `.htaccess` is present in the project root:

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php/$1 [L]
```

#### Virtual Host Example (Apache)

```apache
<VirtualHost *:80>
    DocumentRoot "/path/to/QuickPuff/public"
    ServerName quickpuff.local
    
    <Directory "/path/to/QuickPuff/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### 7. File Permissions

Set appropriate permissions:

```bash
# Make writable directory writable by web server
chmod -R 777 writable/
```

### 8. Access the Application

Open your browser and navigate to:
- **URL**: `http://localhost/QuickPuff/public` or your configured domain
- **Login Page**: You'll be automatically redirected to login

## 👤 Default Credentials

### Admin Account
- **Username**: `admin`
- **Password**: `Admin@123`
- **Role**: Admin (full access)

### Staff Account
- **Username**: `staff`
- **Password**: `staff123`
- **Role**: Staff (POS and Dashboard access)

## 📱 Available Routes

| Route | Method | Access | Description |
|-------|--------|--------|-------------|
| `/` | GET | Public | Redirects to login |
| `/login` | GET | Public | Login page |
| `/auth/attemptLogin` | POST | Public | Process login |
| `/logout` | GET | Public | Logout user |
| `/dashboard` | GET | Authenticated | Main dashboard |
| `/pos` | GET | Authenticated | Point of Sale |
| `/staff` | GET | Admin | Staff list |
| `/staff/create` | GET | Admin | Create staff form |
| `/staff/store` | POST | Admin | Save new staff |
| `/staff/edit/{id}` | GET | Admin | Edit staff form |
| `/staff/update/{id}` | POST | Admin | Update staff |
| `/staff/deactivate/{id}` | GET | Admin | Deactivate staff |
| `/staff/activate/{id}` | GET | Admin | Activate staff |

## 🎨 UI Features

- **Dark Theme**: Modern dark theme with neon accents
- **Responsive Design**: Works on desktop, tablet, and mobile
- **Interactive Elements**: Hover effects, transitions, and animations
- **Real-time Updates**: Cart updates without page refresh
- **Stock Validation**: Prevents overselling with stock checks
- **Role-based UI**: Different interfaces for admin and staff

## 📦 Sample Products

The system comes pre-seeded with 8 sample vape products:

1. **Blue Razz Ice - 100ml** (E-liquid) - ₱24.99
2. **Mango Tango - 100ml** (E-liquid) - ₱22.99
3. **SMOK Nord 4 Kit** (Device) - ₱39.99
4. **Vaporesso XROS 3** (Device) - ₱34.99
5. **SMOK RPM 4 Coils (5-pack)** (Accessory) - ₱15.99
6. **Vaporesso XROS Pods (2-pack)** (Accessory) - ₱12.99
7. **Geek Bar Pulse Disposable** (Device) - ₱18.99
8. **Strawberry Banana - 60ml** (E-liquid) - ₱19.99 (Out of Stock)

## 🔧 Configuration

### Environment Settings

Copy `env` to `.env` and update:

```ini
# App settings
app.baseURL = 'http://localhost/QuickPuff/public/'
app.indexPage = ''

# Database
database.default.hostname = localhost
database.default.database = quickpuff_db
database.default.username = your_username
database.default.password = your_password
database.default.DBDriver = MySQLi
```

### Security Features

- **CSRF Protection**: Enabled by default in CodeIgniter 4
- **Password Hashing**: Uses PHP's `password_hash()`
- **Session Security**: Secure session configuration
- **Input Validation**: Server-side validation on all forms
- **SQL Injection Prevention**: Uses Query Builder

## 🐛 Troubleshooting

### Common Issues

1. **404 Errors**
   - Ensure `mod_rewrite` is enabled in Apache
   - Check `.htaccess` file in project root
   - Verify `app.baseURL` in `.env` file

2. **Database Connection Errors**
   - Verify database credentials in `app/Config/Database.php`
   - Ensure MySQL server is running
   - Check database exists and user has permissions

3. **Permission Issues**
   - Set writable directory permissions: `chmod -R 777 writable/`
   - Ensure web server can write to logs and cache

4. **Session Issues**
   - Check `session.save_path` in PHP configuration
   - Ensure writable directory has proper permissions

### Debug Mode

Enable debug mode by setting in `.env`:

```ini
# CI_ENVIRONMENT = development
```

## 🔄 Development Workflow

### Adding New Features

1. Create controller in `app/Controllers/`
2. Create model in `app/Models/`
3. Create views in `app/Views/`
4. Add routes in `app/Config/Routes.php`
5. Add authentication filters as needed

### Database Changes

1. Create migration: `php spark make:migration CreateNewTable`
2. Run migration: `php spark migrate`
3. Create seeder if needed: `php spark make:seeder NewSeeder`
4. Run seeder: `php spark db:seeder NewSeeder`

## 📝 Next Steps (Future Sprints)

- [ ] Complete checkout process
- [ ] Receipt printing
- [ ] Sales reporting
- [ ] Inventory management
- [ ] Customer management
- [ ] Supplier management
- [ ] Advanced reporting
- [ ] Mobile app

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## 📄 License

This project is proprietary software for Quick Puff Vape Shop.

## 📞 Support

For technical support or issues:
- Check the troubleshooting section above
- Review CodeIgniter 4 documentation
- Contact the development team

---

**Version**: 1.0.0 (Sprint 1)  
**Last Updated**: 2024  
**Framework**: CodeIgniter 4.4.4
