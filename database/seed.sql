-- Domain Reseller Platform - Seed Data
USE domain_reseller;

-- Default Admin User (password: admin123 - CHANGE THIS!)
INSERT INTO users (name, email, password, role, status, email_verified_at) VALUES
('Admin', 'admin@example.com', '$2y$10$UW5d8WaXiMfyQApDKa5t/uVyfPM9EqtPYgFaeiWREV/3o33TB68zu', 'admin', 'active', NOW());

-- Create wallet for admin
INSERT INTO wallets (user_id, balance) VALUES (1, 0.00);

-- Default TLD Pricing
INSERT INTO tld_pricing (tld, register_price, renew_price, transfer_price, currency, is_featured, is_active, registrar, sort_order) VALUES
('.com', 950.00, 1100.00, 950.00, 'BDT', 1, 1, 'namecheap', 1),
('.net', 1100.00, 1200.00, 1100.00, 'BDT', 1, 1, 'namecheap', 2),
('.org', 1050.00, 1200.00, 1050.00, 'BDT', 1, 1, 'namecheap', 3),
('.info', 400.00, 1500.00, 1500.00, 'BDT', 0, 1, 'namecheap', 4),
('.io', 3500.00, 3800.00, 3500.00, 'BDT', 1, 1, 'namecheap', 5),
('.co', 2500.00, 2800.00, 2500.00, 'BDT', 0, 1, 'namecheap', 6),
('.dev', 1200.00, 1400.00, 1200.00, 'BDT', 0, 1, 'namecheap', 7),
('.xyz', 200.00, 1100.00, 1100.00, 'BDT', 1, 1, 'namecheap', 8),
('.online', 300.00, 2500.00, 2500.00, 'BDT', 0, 1, 'namecheap', 9),
('.store', 350.00, 3000.00, 3000.00, 'BDT', 0, 1, 'namecheap', 10),
('.tech', 400.00, 3500.00, 3500.00, 'BDT', 0, 1, 'namecheap', 11),
('.site', 250.00, 2500.00, 2500.00, 'BDT', 0, 1, 'namecheap', 12),
('.me', 800.00, 1800.00, 1800.00, 'BDT', 0, 1, 'namecheap', 13),
('.bd', 1500.00, 1500.00, 1500.00, 'BDT', 1, 1, 'namecheap', 14),
('.com.bd', 1200.00, 1200.00, 1200.00, 'BDT', 1, 1, 'namecheap', 15);

-- Homepage Stats
INSERT INTO homepage_stats (stat_key, stat_label, stat_value, icon, sort_order, is_active) VALUES
('total_domains', 'Domains Registered', '15,000+', 'fas fa-globe', 1, 1),
('happy_customers', 'Happy Customers', '5,000+', 'fas fa-users', 2, 1),
('uptime', 'Uptime Guarantee', '99.9%', 'fas fa-server', 3, 1),
('tlds_available', 'TLDs Available', '500+', 'fas fa-list', 4, 1);

-- Homepage Sections
INSERT INTO homepage_sections (section_key, section_title, section_subtitle, content, is_active, sort_order, settings) VALUES
('hero', 'Find Your Perfect Domain', 'Search from hundreds of domain extensions at the best prices', NULL, 1, 1, '{"bg_color": "#1a1a2e", "text_color": "#ffffff", "cta_text": "Search Domains", "cta_color": "#e94560"}'),
('stats', 'Our Numbers', 'Trusted by thousands of customers', NULL, 1, 2, NULL),
('pricing', 'Domain Pricing', 'Get the best prices for popular domain extensions', NULL, 1, 3, '{"show_featured_only": true}'),
('features', 'Why Choose Us?', 'We offer the best domain registration experience', '[\n  {"icon": "fas fa-shield-alt", "title": "Secure & Reliable", "description": "Your domains are protected with top-notch security and DNS management."},\n  {"icon": "fas fa-headset", "title": "24/7 Support", "description": "Our dedicated support team is always ready to help you."},\n  {"icon": "fas fa-tags", "title": "Best Prices", "description": "Competitive pricing with no hidden fees on domain registration."},\n  {"icon": "fas fa-bolt", "title": "Instant Setup", "description": "Domains are registered instantly with automatic DNS configuration."},\n  {"icon": "fas fa-exchange-alt", "title": "Easy Transfers", "description": "Transfer your domains to us hassle-free with EPP code support."},\n  {"icon": "fas fa-wallet", "title": "Multiple Payment", "description": "Pay with bKash, Nagad, Cards, PayPal and more payment options."}\n]', 1, 4, NULL),
('faq', 'Frequently Asked Questions', NULL, '[\n  {"q": "How do I register a domain?", "a": "Simply search for your desired domain name, add it to cart, and complete the checkout process."},\n  {"q": "How long does domain registration take?", "a": "Domain registration is usually instant. You will receive a confirmation email once the domain is active."},\n  {"q": "Can I transfer my domain to you?", "a": "Yes! You can transfer your domain by providing the EPP/Auth code from your current registrar."},\n  {"q": "What payment methods do you accept?", "a": "We accept bKash, Nagad, SSLCommerz, Stripe, PayPal, Razorpay, and bank transfers."},\n  {"q": "Do you offer WHOIS privacy?", "a": "Yes, WHOIS privacy protection is available for supported domain extensions."},\n  {"q": "How do I manage DNS records?", "a": "You can manage DNS records directly from your account dashboard after domain registration."}\n]', 1, 5, NULL),
('testimonials', 'What Our Customers Say', NULL, '[\n  {"name": "Rahim Ahmed", "company": "TechBD", "text": "Excellent service with the best domain prices in Bangladesh. Highly recommended!", "rating": 5},\n  {"name": "Fatima Sultana", "company": "WebStar BD", "text": "The support team is amazing. They helped me transfer all my domains smoothly.", "rating": 5},\n  {"name": "Kamal Hassan", "company": "Digital Agency", "text": "Great platform with easy-to-use dashboard and multiple payment options.", "rating": 4}\n]', 1, 6, NULL);

-- Default Site Settings
INSERT INTO site_settings (setting_key, setting_value, setting_group) VALUES
('site_name', 'Domain Reseller', 'general'),
('site_tagline', 'Your Trusted Domain Partner', 'general'),
('site_logo', '/assets/images/logo.png', 'general'),
('site_favicon', '/assets/images/favicon.ico', 'general'),
('active_font', '''Tiro Bangla'', serif', 'appearance'),
('active_theme_home', 'default', 'appearance'),
('active_theme_account', 'default', 'appearance'),
('maintenance_mode', '0', 'general'),
('maintenance_message', 'We are currently performing maintenance. Please check back soon.', 'general'),
('registration_enabled', '1', 'auth'),
('social_login_enabled', '0', 'auth'),
('verification_required', '0', 'auth'),
('default_registrar', 'namecheap', 'registrar'),
('affiliate_enabled', '0', 'affiliate'),
('affiliate_commission_rate', '10', 'affiliate'),
('wallet_enabled', '1', 'wallet'),
('telegram_enabled', '0', 'notification'),
('whatsapp_enabled', '0', 'notification'),
('email_enabled', '1', 'notification'),
('currency', 'BDT', 'billing'),
('currency_symbol', '৳', 'billing'),
('invoice_prefix', 'INV-', 'billing'),
('order_prefix', 'ORD-', 'billing');

-- Default Email Templates
INSERT INTO email_templates (slug, name, subject, body_html, variables, is_active) VALUES
('welcome', 'Welcome Email', 'Welcome to {{app_name}}!', '<h2>Welcome, {{name}}!</h2><p>Thank you for joining {{app_name}}. Your account has been created successfully.</p><p>You can now search and register domain names at the best prices.</p>', 'name,email,app_name', 1),
('order_confirmation', 'Order Confirmation', 'Order #{{order_number}} Confirmed', '<h2>Order Confirmed</h2><p>Hi {{name}},</p><p>Your order #{{order_number}} has been received and is being processed.</p><p><strong>Total:</strong> {{currency_symbol}}{{total}}</p>', 'name,order_number,total,currency_symbol', 1),
('payment_received', 'Payment Received', 'Payment Received for Order #{{order_number}}', '<h2>Payment Received</h2><p>Hi {{name}},</p><p>We have received your payment of {{currency_symbol}}{{amount}} for order #{{order_number}}.</p>', 'name,order_number,amount,currency_symbol', 1),
('domain_registered', 'Domain Registered', 'Domain {{domain}} Registered Successfully', '<h2>Domain Registered!</h2><p>Hi {{name}},</p><p>Your domain <strong>{{domain}}</strong> has been registered successfully and is now active.</p>', 'name,domain', 1),
('domain_expiring', 'Domain Expiring Soon', 'Your domain {{domain}} expires in {{days}} days', '<h2>Domain Expiring Soon</h2><p>Hi {{name}},</p><p>Your domain <strong>{{domain}}</strong> will expire on {{expiry_date}}. Please renew it to avoid losing it.</p>', 'name,domain,expiry_date,days', 1),
('ticket_reply', 'Ticket Reply', 'Reply to Ticket #{{ticket_id}}: {{subject}}', '<h2>New Reply</h2><p>Hi {{name}},</p><p>There is a new reply to your support ticket #{{ticket_id}}: {{subject}}</p><p>{{message}}</p>', 'name,ticket_id,subject,message', 1),
('password_reset', 'Password Reset', 'Reset Your Password', '<h2>Password Reset</h2><p>Hi {{name}},</p><p>Click the link below to reset your password:</p><p><a href="{{reset_link}}">Reset Password</a></p><p>This link expires in 1 hour.</p>', 'name,reset_link', 1),
('verification_approved', 'Verification Approved', 'Your Identity Verified', '<h2>Verification Approved</h2><p>Hi {{name}},</p><p>Your identity verification has been approved. You now have full access to all features.</p>', 'name', 1),
('verification_rejected', 'Verification Rejected', 'Verification Update', '<h2>Verification Update</h2><p>Hi {{name}},</p><p>Your identity verification was not approved. Reason: {{reason}}</p><p>Please resubmit with valid documents.</p>', 'name,reason', 1);

-- Default Theme
INSERT INTO themes (name, slug, description, version, author, is_active_home, is_active_account) VALUES
('Default', 'default', 'Default theme with clean modern design', '1.0.0', 'Domain Reseller', 1, 1);

-- Default Font Settings
INSERT INTO font_settings (font_key, font_name, font_family, font_type, font_url, is_active) VALUES
('tiro_bangla', 'Tiro Bangla', '''Tiro Bangla'', serif', 'local', NULL, 1),
('noto_sans_bengali', 'Noto Sans Bengali', '''Noto Sans Bengali'', sans-serif', 'google', 'https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;500;600;700&display=swap', 0),
('hind_siliguri', 'Hind Siliguri', '''Hind Siliguri'', sans-serif', 'google', 'https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap', 0),
('inter', 'Inter', '''Inter'', sans-serif', 'google', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap', 0),
('poppins', 'Poppins', '''Poppins'', sans-serif', 'google', 'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap', 0);

-- Default SEO Settings
INSERT INTO seo_settings (page_key, meta_title, meta_description, meta_keywords) VALUES
('home', 'Domain Reseller - Register Domains at Best Prices', 'Register, transfer, and manage domain names at competitive prices. Trusted by thousands of customers.', 'domain registration, domain reseller, buy domain, domain Bangladesh, cheap domain'),
('search', 'Search Domain Names', 'Search and check domain availability for hundreds of extensions.', 'domain search, check domain, domain availability'),
('login', 'Login to Your Account', 'Sign in to manage your domains, orders, and account settings.', 'login, sign in, account'),
('register', 'Create an Account', 'Sign up to start registering domains at the best prices.', 'register, sign up, create account');
