-- Domain Reseller Platform - Database Schema
-- MySQL 8.0+

SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

CREATE DATABASE IF NOT EXISTS domain_reseller
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE domain_reseller;

-- ============================================
-- Users & Authentication
-- ============================================

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NULL,
    role ENUM('customer', 'moderator', 'admin') NOT NULL DEFAULT 'customer',
    status ENUM('active', 'suspended', 'banned') NOT NULL DEFAULT 'active',
    avatar VARCHAR(500) NULL,
    phone VARCHAR(50) NULL,
    company VARCHAR(255) NULL,
    address TEXT NULL,
    city VARCHAR(100) NULL,
    state VARCHAR(100) NULL,
    country VARCHAR(100) NULL DEFAULT 'BD',
    zip_code VARCHAR(20) NULL,
    email_verified_at TIMESTAMP NULL,
    social_provider VARCHAR(50) NULL,
    social_id VARCHAR(255) NULL,
    referral_code VARCHAR(50) NULL UNIQUE,
    referred_by INT UNSIGNED NULL,
    remember_token VARCHAR(100) NULL,
    reset_token VARCHAR(100) NULL,
    reset_token_expires TIMESTAMP NULL,
    last_login_at TIMESTAMP NULL,
    last_login_ip VARCHAR(45) NULL,
    theme_preference ENUM('light', 'dark', 'system') NOT NULL DEFAULT 'system',
    locale VARCHAR(10) NOT NULL DEFAULT 'en',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_referral_code (referral_code),
    FOREIGN KEY (referred_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================
-- Identity Verification (KYC)
-- ============================================

CREATE TABLE identity_verifications (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    document_type ENUM('nid', 'passport', 'driving_license', 'other') NOT NULL DEFAULT 'nid',
    document_number VARCHAR(100) NULL,
    front_image VARCHAR(500) NOT NULL,
    back_image VARCHAR(500) NULL,
    selfie_image VARCHAR(500) NULL,
    status ENUM('pending', 'under_review', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    admin_notes TEXT NULL,
    reviewed_by INT UNSIGNED NULL,
    reviewed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- ============================================
-- Domains
-- ============================================

CREATE TABLE domains (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    domain_name VARCHAR(255) NOT NULL,
    tld VARCHAR(50) NOT NULL,
    registrar VARCHAR(50) NOT NULL DEFAULT 'namecheap',
    registrar_domain_id VARCHAR(255) NULL,
    status ENUM('active', 'pending', 'expired', 'cancelled', 'transferring') NOT NULL DEFAULT 'pending',
    registration_date DATE NULL,
    expiry_date DATE NULL,
    auto_renew TINYINT(1) NOT NULL DEFAULT 0,
    whois_privacy TINYINT(1) NOT NULL DEFAULT 0,
    is_locked TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_domain_name (domain_name),
    INDEX idx_status (status),
    INDEX idx_expiry_date (expiry_date)
) ENGINE=InnoDB;

-- ============================================
-- Nameservers
-- ============================================

CREATE TABLE nameservers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    domain_id INT UNSIGNED NOT NULL,
    nameserver VARCHAR(255) NOT NULL,
    sort_order TINYINT UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (domain_id) REFERENCES domains(id) ON DELETE CASCADE,
    INDEX idx_domain_id (domain_id)
) ENGINE=InnoDB;

-- ============================================
-- DNS Records
-- ============================================

CREATE TABLE dns_records (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    domain_id INT UNSIGNED NOT NULL,
    record_type ENUM('A', 'AAAA', 'CNAME', 'MX', 'TXT', 'NS', 'SRV') NOT NULL,
    name VARCHAR(255) NOT NULL,
    value VARCHAR(1000) NOT NULL,
    ttl INT UNSIGNED NOT NULL DEFAULT 3600,
    priority INT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (domain_id) REFERENCES domains(id) ON DELETE CASCADE,
    INDEX idx_domain_id (domain_id)
) ENGINE=InnoDB;

-- ============================================
-- Domain Transfers
-- ============================================

CREATE TABLE transfers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    domain_name VARCHAR(255) NOT NULL,
    tld VARCHAR(50) NOT NULL,
    epp_code VARCHAR(255) NOT NULL,
    registrar VARCHAR(50) NOT NULL,
    status ENUM('pending', 'processing', 'completed', 'failed', 'cancelled') NOT NULL DEFAULT 'pending',
    registrar_transfer_id VARCHAR(255) NULL,
    error_message TEXT NULL,
    order_id INT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- ============================================
-- WHOIS Privacy
-- ============================================

CREATE TABLE whois_privacy (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    domain_id INT UNSIGNED NOT NULL UNIQUE,
    enabled TINYINT(1) NOT NULL DEFAULT 0,
    registrar_privacy_id VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (domain_id) REFERENCES domains(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================
-- Domain Contact Info
-- ============================================

CREATE TABLE domain_contacts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    contact_type ENUM('registrant', 'admin', 'tech', 'billing') NOT NULL DEFAULT 'registrant',
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    organization VARCHAR(255) NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    address1 VARCHAR(255) NOT NULL,
    address2 VARCHAR(255) NULL,
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100) NOT NULL,
    country VARCHAR(10) NOT NULL DEFAULT 'BD',
    zip_code VARCHAR(20) NOT NULL,
    is_default TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id)
) ENGINE=InnoDB;

-- ============================================
-- TLD Pricing
-- ============================================

CREATE TABLE tld_pricing (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tld VARCHAR(50) NOT NULL UNIQUE,
    register_price DECIMAL(10,2) NOT NULL,
    renew_price DECIMAL(10,2) NOT NULL,
    transfer_price DECIMAL(10,2) NOT NULL,
    currency VARCHAR(10) NOT NULL DEFAULT 'BDT',
    is_featured TINYINT(1) NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    min_years TINYINT UNSIGNED NOT NULL DEFAULT 1,
    max_years TINYINT UNSIGNED NOT NULL DEFAULT 10,
    registrar VARCHAR(50) NOT NULL DEFAULT 'namecheap',
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_tld (tld),
    INDEX idx_featured (is_featured)
) ENGINE=InnoDB;

-- ============================================
-- Shopping Cart
-- ============================================

CREATE TABLE carts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NULL,
    session_id VARCHAR(255) NULL,
    coupon_id INT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    INDEX idx_session_id (session_id)
) ENGINE=InnoDB;

CREATE TABLE cart_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cart_id INT UNSIGNED NOT NULL,
    domain_name VARCHAR(255) NOT NULL,
    tld VARCHAR(50) NOT NULL,
    item_type ENUM('register', 'transfer', 'renew') NOT NULL DEFAULT 'register',
    years TINYINT UNSIGNED NOT NULL DEFAULT 1,
    price DECIMAL(10,2) NOT NULL,
    epp_code VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cart_id) REFERENCES carts(id) ON DELETE CASCADE,
    INDEX idx_cart_id (cart_id)
) ENGINE=InnoDB;

-- ============================================
-- Orders
-- ============================================

CREATE TABLE orders (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    order_number VARCHAR(50) NOT NULL UNIQUE,
    status ENUM('pending', 'processing', 'completed', 'cancelled', 'refunded', 'failed') NOT NULL DEFAULT 'pending',
    subtotal DECIMAL(10,2) NOT NULL DEFAULT 0,
    discount DECIMAL(10,2) NOT NULL DEFAULT 0,
    total DECIMAL(10,2) NOT NULL DEFAULT 0,
    currency VARCHAR(10) NOT NULL DEFAULT 'BDT',
    coupon_id INT UNSIGNED NULL,
    payment_method VARCHAR(50) NULL,
    payment_status ENUM('pending', 'paid', 'failed', 'refunded') NOT NULL DEFAULT 'pending',
    notes TEXT NULL,
    admin_notes TEXT NULL,
    ip_address VARCHAR(45) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_order_number (order_number),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB;

CREATE TABLE order_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id INT UNSIGNED NOT NULL,
    domain_name VARCHAR(255) NOT NULL,
    tld VARCHAR(50) NOT NULL,
    item_type ENUM('register', 'transfer', 'renew') NOT NULL DEFAULT 'register',
    years TINYINT UNSIGNED NOT NULL DEFAULT 1,
    price DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'processing', 'completed', 'failed') NOT NULL DEFAULT 'pending',
    registrar VARCHAR(50) NULL,
    registrar_order_id VARCHAR(255) NULL,
    error_message TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    INDEX idx_order_id (order_id)
) ENGINE=InnoDB;

-- ============================================
-- Invoices
-- ============================================

CREATE TABLE invoices (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    order_id INT UNSIGNED NULL,
    invoice_number VARCHAR(50) NOT NULL UNIQUE,
    status ENUM('unpaid', 'paid', 'cancelled', 'refunded') NOT NULL DEFAULT 'unpaid',
    subtotal DECIMAL(10,2) NOT NULL,
    discount DECIMAL(10,2) NOT NULL DEFAULT 0,
    total DECIMAL(10,2) NOT NULL,
    currency VARCHAR(10) NOT NULL DEFAULT 'BDT',
    due_date DATE NULL,
    paid_at TIMESTAMP NULL,
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_invoice_number (invoice_number)
) ENGINE=InnoDB;

-- ============================================
-- Payments
-- ============================================

CREATE TABLE payments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    order_id INT UNSIGNED NULL,
    invoice_id INT UNSIGNED NULL,
    gateway VARCHAR(50) NOT NULL,
    transaction_id VARCHAR(255) NULL,
    amount DECIMAL(10,2) NOT NULL,
    currency VARCHAR(10) NOT NULL DEFAULT 'BDT',
    status ENUM('pending', 'completed', 'failed', 'refunded') NOT NULL DEFAULT 'pending',
    gateway_response TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL,
    FOREIGN KEY (invoice_id) REFERENCES invoices(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_transaction_id (transaction_id)
) ENGINE=InnoDB;

-- ============================================
-- Coupons
-- ============================================

CREATE TABLE coupons (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    type ENUM('percentage', 'fixed') NOT NULL DEFAULT 'percentage',
    value DECIMAL(10,2) NOT NULL,
    min_order DECIMAL(10,2) NULL,
    max_discount DECIMAL(10,2) NULL,
    max_uses INT UNSIGNED NULL,
    used_count INT UNSIGNED NOT NULL DEFAULT 0,
    per_user_limit INT UNSIGNED NULL DEFAULT 1,
    valid_from TIMESTAMP NULL,
    valid_until TIMESTAMP NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    applicable_tlds TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_code (code),
    INDEX idx_active (is_active)
) ENGINE=InnoDB;

-- ============================================
-- Support Tickets
-- ============================================

CREATE TABLE tickets (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    subject VARCHAR(255) NOT NULL,
    department ENUM('sales', 'billing', 'technical', 'general') NOT NULL DEFAULT 'general',
    priority ENUM('low', 'medium', 'high', 'urgent') NOT NULL DEFAULT 'medium',
    status ENUM('open', 'in_progress', 'waiting', 'closed') NOT NULL DEFAULT 'open',
    assigned_to INT UNSIGNED NULL,
    related_order_id INT UNSIGNED NULL,
    related_domain_id INT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    closed_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_status (status)
) ENGINE=InnoDB;

CREATE TABLE ticket_replies (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ticket_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    message TEXT NOT NULL,
    is_staff TINYINT(1) NOT NULL DEFAULT 0,
    attachment VARCHAR(500) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_ticket_id (ticket_id)
) ENGINE=InnoDB;

-- ============================================
-- Wallet System
-- ============================================

CREATE TABLE wallets (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL UNIQUE,
    balance DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE wallet_transactions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    wallet_id INT UNSIGNED NOT NULL,
    type ENUM('credit', 'debit') NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    balance_after DECIMAL(10,2) NOT NULL,
    description VARCHAR(500) NOT NULL,
    reference_type VARCHAR(50) NULL,
    reference_id INT UNSIGNED NULL,
    created_by INT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (wallet_id) REFERENCES wallets(id) ON DELETE CASCADE,
    INDEX idx_wallet_id (wallet_id)
) ENGINE=InnoDB;

-- ============================================
-- Affiliate System
-- ============================================

CREATE TABLE affiliates (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL UNIQUE,
    status ENUM('active', 'inactive', 'suspended') NOT NULL DEFAULT 'active',
    commission_rate DECIMAL(5,2) NOT NULL DEFAULT 10.00,
    total_earnings DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    paid_earnings DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    total_referrals INT UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE affiliate_transactions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    affiliate_id INT UNSIGNED NOT NULL,
    order_id INT UNSIGNED NULL,
    referred_user_id INT UNSIGNED NULL,
    amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'approved', 'paid', 'rejected') NOT NULL DEFAULT 'pending',
    description VARCHAR(500) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (affiliate_id) REFERENCES affiliates(id) ON DELETE CASCADE,
    INDEX idx_affiliate_id (affiliate_id)
) ENGINE=InnoDB;

-- ============================================
-- Announcements
-- ============================================

CREATE TABLE announcements (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    type ENUM('info', 'warning', 'success', 'danger') NOT NULL DEFAULT 'info',
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    show_on_homepage TINYINT(1) NOT NULL DEFAULT 1,
    starts_at TIMESTAMP NULL,
    ends_at TIMESTAMP NULL,
    created_by INT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================
-- Homepage Stats & Sections
-- ============================================

CREATE TABLE homepage_stats (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    stat_key VARCHAR(100) NOT NULL UNIQUE,
    stat_label VARCHAR(255) NOT NULL,
    stat_value VARCHAR(100) NOT NULL,
    icon VARCHAR(50) NULL,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE homepage_sections (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    section_key VARCHAR(100) NOT NULL UNIQUE,
    section_title VARCHAR(255) NULL,
    section_subtitle TEXT NULL,
    content LONGTEXT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    sort_order INT NOT NULL DEFAULT 0,
    settings JSON NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================
-- Themes
-- ============================================

CREATE TABLE themes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT NULL,
    version VARCHAR(20) NULL DEFAULT '1.0.0',
    author VARCHAR(255) NULL,
    preview_image VARCHAR(500) NULL,
    is_active_home TINYINT(1) NOT NULL DEFAULT 0,
    is_active_account TINYINT(1) NOT NULL DEFAULT 0,
    installed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================
-- Email Templates
-- ============================================

CREATE TABLE email_templates (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(100) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    body_html TEXT NOT NULL,
    body_text TEXT NULL,
    variables TEXT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================
-- Font Settings
-- ============================================

CREATE TABLE font_settings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    font_key VARCHAR(100) NOT NULL,
    font_name VARCHAR(255) NOT NULL,
    font_family VARCHAR(255) NOT NULL,
    font_type ENUM('local', 'google', 'custom') NOT NULL DEFAULT 'google',
    font_url VARCHAR(500) NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_font_key (font_key)
) ENGINE=InnoDB;

-- ============================================
-- SEO Settings
-- ============================================

CREATE TABLE seo_settings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    page_key VARCHAR(100) NOT NULL UNIQUE,
    meta_title VARCHAR(255) NULL,
    meta_description TEXT NULL,
    meta_keywords VARCHAR(500) NULL,
    og_image VARCHAR(500) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================
-- Site Settings (Key-Value)
-- ============================================

CREATE TABLE site_settings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT NULL,
    setting_group VARCHAR(50) NOT NULL DEFAULT 'general',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_setting_key (setting_key),
    INDEX idx_setting_group (setting_group)
) ENGINE=InnoDB;

-- ============================================
-- Notification Log
-- ============================================

CREATE TABLE notification_logs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    channel ENUM('telegram', 'whatsapp', 'email') NOT NULL,
    event VARCHAR(100) NOT NULL,
    recipient VARCHAR(255) NULL,
    message TEXT NOT NULL,
    status ENUM('sent', 'failed') NOT NULL DEFAULT 'sent',
    error_message TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_channel (channel),
    INDEX idx_event (event)
) ENGINE=InnoDB;

-- ============================================
-- Activity Log
-- ============================================

CREATE TABLE activity_logs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NULL,
    action VARCHAR(100) NOT NULL,
    description TEXT NULL,
    model_type VARCHAR(100) NULL,
    model_id INT UNSIGNED NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_action (action),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB;

-- ============================================
-- Migrations for existing installations
-- (Safe to run on a fresh schema; will no-op if column exists.)
-- ============================================

-- Add theme_preference column to users (added 2026-05)
-- Run this only if upgrading from a pre-dark-mode install:
--   ALTER TABLE users ADD COLUMN theme_preference ENUM('light','dark','system') NOT NULL DEFAULT 'system';
--   ALTER TABLE users ADD COLUMN locale VARCHAR(10) NOT NULL DEFAULT 'en';
