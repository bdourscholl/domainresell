# Domain Reseller Platform

A complete PHP domain name reseller/seller platform built from scratch without WHMCS. Full admin control over appearance, notifications, payment gateways, registrar integrations, and theme system.

## Features

### Domain Operations
- Multi-registrar domain search (Namecheap, Spaceship, Cloudflare, Mock)
- Domain registration, transfer (EPP code), and renewal
- DNS management (A, AAAA, CNAME, MX, TXT, NS, SRV)
- Custom nameserver management
- WHOIS privacy toggle per domain

### Payment Gateways (All Admin-Toggleable)
- Stripe, PayPal, Razorpay
- SSLCommerz, bKash, Nagad (Bangladesh local)
- Manual / Bank Transfer

### Notifications (All Admin-Toggleable)
- Telegram Bot (instant alerts)
- WhatsApp Business API
- Email (SMTP)

### Authentication
- Email/password registration & login
- Social login: Google OAuth, Facebook Login, GitHub OAuth
- Password reset via email

### Identity Verification (KYC)
- NID/Passport/Driving license upload
- Admin review queue with approve/reject
- Admin can toggle verification requirement on/off

### Homepage (Full CMS Control)
- Admin-editable statistics counters (domains registered, customers, uptime, etc.)
- Hero banner with search bar
- Featured TLD pricing table
- Features/why choose us section
- FAQ accordion
- Customer testimonials
- All sections can be reordered, shown/hidden

### Theme System
- Uploadable ZIP themes
- Separate active theme for homepage vs account dashboard
- Fallback to base templates

### Billing & Commerce
- Shopping cart with coupon/promo code support
- Invoice generation with PDF export
- Wallet/balance system
- Affiliate/referral system with commission tracking

### Admin Panel
- Dashboard with stats, recent orders
- Homepage content management
- Customer management
- TLD pricing management
- Coupon management
- Support ticket system
- Theme & appearance management
- Font management (Tiro Bangla default)
- Payment gateway configuration
- Registrar configuration
- Notification settings
- Moderator management
- Email template editor
- SEO settings per page
- Language/translation management
- Announcements
- Maintenance mode

### Localization
- English (EN) and Bengali (BN) translations
- Admin-editable translation strings

### Background Tasks (Cron)
- Domain status sync
- Expiry reminders (30/15/7/3/1 days)
- Registrar pricing sync
- Cache cleanup

## Requirements

- PHP 8.1+
- MySQL 8.0+
- Composer
- Apache with mod_rewrite (or Nginx)

## Installation

### 1. Clone and install dependencies
```bash
git clone <repository-url> domain-reseller
cd domain-reseller
composer install
```

### 2. Configure environment
```bash
cp .env.example .env
# Edit .env with your database credentials, API keys, etc.
```

### 3. Create database and import schema
```bash
mysql -u root -p -e "CREATE DATABASE domain_reseller CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
mysql -u root -p domain_reseller < database/schema.sql
mysql -u root -p domain_reseller < database/seed.sql
```

### 4. Set permissions
```bash
chmod -R 775 storage/
chmod -R 775 public/assets/themes/
```

### 5. Configure web server

**Apache** - Point document root to the `public/` directory. The `.htaccess` file handles URL rewriting.

**Nginx** - Example config:
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/domain-reseller/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### 6. Setup cron jobs
```bash
# Add to crontab
*/5 * * * * php /path/to/domain-reseller/bin/console sync:domains
0 9 * * * php /path/to/domain-reseller/bin/console notify:expiry
0 3 * * 0 php /path/to/domain-reseller/bin/console sync:registrars
0 4 * * * php /path/to/domain-reseller/bin/console cache:cleanup
```

## Default Admin Login
- **Email:** admin@example.com
- **Password:** admin123 (CHANGE IMMEDIATELY)

## Project Structure

```
domain-reseller/
├── bin/console              # CLI entry point
├── config/                  # Configuration files
├── database/                # Schema & seed SQL
├── public/                  # Web root (only this is exposed)
│   ├── index.php            # Front controller
│   ├── .htaccess            # URL rewriting
│   └── assets/              # CSS, JS, images, fonts
├── resources/lang/          # Localization (en/, bn/)
├── src/
│   ├── Core/                # MVC framework
│   ├── Console/             # CLI commands
│   ├── Controllers/         # Public & Admin controllers
│   ├── Models/              # Data models (30+)
│   ├── Services/            # Business logic & integrations
│   ├── Middleware/           # Auth, CSRF, Locale, etc.
│   └── Helpers/             # Utility functions
├── templates/               # Base fallback templates
├── themes/                  # Uploadable themes
├── storage/                 # Logs, cache, uploads
├── .env.example             # Environment template
├── composer.json            # PHP dependencies
└── README.md
```

## Security

- `.env` file is outside web root
- All config/src files are outside web root
- CSRF protection on all POST requests
- Password hashing with bcrypt
- Input validation and sanitization
- File upload validation
- Session-based authentication

## Default Configuration

- **Timezone:** Asia/Dhaka
- **Currency:** BDT (৳)
- **Locale:** English (en)
- **Font:** Tiro Bangla (local TTF)
- **Registrar:** Mock (for testing)

## License

Proprietary - All rights reserved.
