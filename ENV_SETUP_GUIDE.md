# NPTF Backend - Environment Configuration Guide

## Database Configuration (MySQL)

Update the following lines in `/backend/.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nptf_db
DB_USERNAME=root
DB_PASSWORD=your_mysql_password
```

## Email Configuration (Gmail SMTP)

Add/update these lines in `/backend/.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-gmail@gmail.com
MAIL_PASSWORD=your-gmail-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=info@nptf.gov.ng
MAIL_FROM_NAME="Nigeria Police Trust Fund"
```

**Important**: You need to generate a Gmail App Password:
1. Go to Google Account settings
2. Enable 2-Factor Authentication
3. Go to Security → App Passwords
4. Generate password for "Mail"
5. Use that password in MAIL_PASSWORD

## Sanctum Configuration

Add these lines:

```env
SANCTUM_STATEFUL_DOMAINS=localhost:3000
SESSION_DOMAIN=localhost
```

## CORS Configuration

Add this line:

```env
CORS_ALLOWED_ORIGINS=http://localhost:3000
```

## Application URL

Update:

```env
APP_URL=http://localhost:8000
```

## Complete .env Example

```env
APP_NAME="NPTF Backend"
APP_ENV=local
APP_KEY=base64:... (already generated)
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nptf_db
DB_USERNAME=root
DB_PASSWORD=your_password

# Email (Gmail)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-gmail@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=info@nptf.gov.ng
MAIL_FROM_NAME="Nigeria Police Trust Fund"

# Sanctum
SANCTUM_STATEFUL_DOMAINS=localhost:3000
SESSION_DOMAIN=localhost

# CORS
CORS_ALLOWED_ORIGINS=http://localhost:3000
```

## Next Steps

1. Create MySQL database: `CREATE DATABASE nptf_db;`
2. Run migrations: `php artisan migrate`
3. Seed admin user: `php artisan db:seed`
