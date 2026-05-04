<?php

declare(strict_types=1);

namespace App\Core;

class App
{
    private static ?App $instance = null;
    private Router $router;
    private Database $db;
    private Session $session;
    private array $config = [];

    public function __construct()
    {
        self::$instance = $this;
        $this->loadConfig();
        $this->db = new Database($this->config['database']);
        $this->session = new Session();
        $this->router = new Router();
        $this->registerRoutes();
    }

    public static function getInstance(): ?App
    {
        return self::$instance;
    }

    public function run(): void
    {
        $request = new Request();
        $response = $this->router->dispatch($request);
        $response->send();
    }

    public function getDb(): Database
    {
        return $this->db;
    }

    public function getSession(): Session
    {
        return $this->session;
    }

    public function getRouter(): Router
    {
        return $this->router;
    }

    public function config(string $key, mixed $default = null): mixed
    {
        $keys = explode('.', $key);
        $value = $this->config;
        foreach ($keys as $k) {
            if (!isset($value[$k])) {
                return $default;
            }
            $value = $value[$k];
        }
        return $value;
    }

    private function loadConfig(): void
    {
        $configPath = BASE_PATH . '/config';
        foreach (glob($configPath . '/*.php') as $file) {
            $name = basename($file, '.php');
            $this->config[$name] = require $file;
        }
    }

    private function registerRoutes(): void
    {
        // Public routes
        $this->router->get('/', 'HomeController@index');
        $this->router->get('/search', 'HomeController@search');
        $this->router->get('/search/ajax', 'HomeController@searchAjax');
        $this->router->get('/domain/{domain}', 'DomainController@details');

        // Auth routes
        $this->router->get('/login', 'AuthController@loginForm');
        $this->router->post('/login', 'AuthController@login');
        $this->router->get('/register', 'AuthController@registerForm');
        $this->router->post('/register', 'AuthController@register');
        $this->router->get('/forgot-password', 'AuthController@forgotPasswordForm');
        $this->router->post('/forgot-password', 'AuthController@forgotPassword');
        $this->router->get('/reset-password', 'AuthController@resetPasswordForm');
        $this->router->post('/reset-password', 'AuthController@resetPassword');
        $this->router->get('/logout', 'AuthController@logout');

        // Social Auth
        $this->router->get('/auth/{provider}/redirect', 'SocialAuthController@redirect');
        $this->router->get('/auth/{provider}/callback', 'SocialAuthController@callback');

        // Domain Transfer
        $this->router->get('/transfer', 'TransferController@form');
        $this->router->post('/transfer', 'TransferController@submit');

        // Cart
        $this->router->get('/cart', 'CartController@index');
        $this->router->post('/cart/add', 'CartController@add');
        $this->router->post('/cart/remove/{id}', 'CartController@remove');
        $this->router->post('/cart/coupon', 'CartController@applyCoupon');

        // Checkout
        $this->router->get('/checkout', 'CheckoutController@index', ['auth']);
        $this->router->post('/checkout/process', 'CheckoutController@process', ['auth']);
        $this->router->get('/checkout/success/{id}', 'CheckoutController@success', ['auth']);
        $this->router->get('/checkout/cancel', 'CheckoutController@cancel', ['auth']);

        // Webhooks (no CSRF)
        $this->router->post('/webhook/stripe', 'WebhookController@stripe');
        $this->router->post('/webhook/paypal', 'WebhookController@paypal');
        $this->router->post('/webhook/razorpay', 'WebhookController@razorpay');
        $this->router->post('/webhook/sslcommerz', 'WebhookController@sslcommerz');
        $this->router->post('/webhook/bkash', 'WebhookController@bkash');
        $this->router->post('/webhook/nagad', 'WebhookController@nagad');

        // User Account (auth required)
        $this->router->get('/account', 'AccountController@dashboard', ['auth']);
        $this->router->get('/account/profile', 'AccountController@profile', ['auth']);
        $this->router->post('/account/profile', 'AccountController@updateProfile', ['auth']);
        $this->router->post('/account/change-password', 'AccountController@changePassword', ['auth']);
        $this->router->get('/account/domains', 'DomainController@myDomains', ['auth']);
        $this->router->get('/account/orders', 'OrderController@index', ['auth']);
        $this->router->get('/account/orders/{id}', 'OrderController@show', ['auth']);
        $this->router->get('/account/invoices', 'InvoiceController@index', ['auth']);
        $this->router->get('/account/invoices/{id}/download', 'InvoiceController@download', ['auth']);

        // Nameserver Management
        $this->router->get('/account/domains/{id}/nameservers', 'NameserverController@index', ['auth']);
        $this->router->post('/account/domains/{id}/nameservers', 'NameserverController@update', ['auth']);

        // DNS Management
        $this->router->get('/account/domains/{id}/dns', 'DnsController@index', ['auth']);
        $this->router->post('/account/domains/{id}/dns', 'DnsController@add', ['auth']);
        $this->router->post('/account/domains/{id}/dns/{recordId}/delete', 'DnsController@delete', ['auth']);

        // WHOIS Privacy
        $this->router->get('/account/domains/{id}/privacy', 'PrivacyController@index', ['auth']);
        $this->router->post('/account/domains/{id}/privacy', 'PrivacyController@toggle', ['auth']);

        // Domain Renewal
        $this->router->get('/account/renewals', 'RenewalController@index', ['auth']);
        $this->router->post('/account/renewals/{id}', 'RenewalController@renew', ['auth']);

        // Wallet
        $this->router->get('/account/wallet', 'WalletController@index', ['auth']);

        // Affiliate
        $this->router->get('/account/affiliate', 'AffiliateController@index', ['auth']);

        // Verification
        $this->router->get('/account/verification', 'VerificationController@index', ['auth']);
        $this->router->post('/account/verification', 'VerificationController@submit', ['auth']);

        // Tickets
        $this->router->get('/account/tickets', 'TicketController@index', ['auth']);
        $this->router->post('/account/tickets', 'TicketController@create', ['auth']);
        $this->router->get('/account/tickets/{id}', 'TicketController@show', ['auth']);
        $this->router->post('/account/tickets/{id}/reply', 'TicketController@reply', ['auth']);

        // Language switch
        $this->router->get('/lang/{locale}', 'HomeController@setLocale');

        // Theme preference (works for guests too — guests get success but no persistence)
        $this->router->post('/api/account/theme', 'AccountController@setTheme');

        // ── Admin Routes ──
        $this->router->get('/admin', 'Admin\\DashboardController@index', ['auth', 'admin']);
        $this->router->get('/admin/homepage', 'Admin\\HomepageController@index', ['auth', 'admin']);
        $this->router->post('/admin/homepage', 'Admin\\HomepageController@update', ['auth', 'admin']);
        $this->router->post('/admin/homepage/stats', 'Admin\\HomepageController@updateStats', ['auth', 'admin']);
        $this->router->post('/admin/homepage/sections', 'Admin\\HomepageController@updateSections', ['auth', 'admin']);

        $this->router->get('/admin/domains', 'Admin\\DomainController@index', ['auth', 'admin']);
        $this->router->get('/admin/domains/{id}', 'Admin\\DomainController@show', ['auth', 'admin']);

        $this->router->get('/admin/orders', 'Admin\\OrderController@index', ['auth', 'admin']);
        $this->router->get('/admin/orders/{id}', 'Admin\\OrderController@show', ['auth', 'admin']);
        $this->router->post('/admin/orders/{id}/status', 'Admin\\OrderController@updateStatus', ['auth', 'admin']);

        $this->router->get('/admin/invoices', 'Admin\\InvoiceController@index', ['auth', 'admin']);

        $this->router->get('/admin/customers', 'Admin\\CustomerController@index', ['auth', 'admin']);
        $this->router->get('/admin/customers/{id}', 'Admin\\CustomerController@show', ['auth', 'admin']);

        $this->router->get('/admin/verifications', 'Admin\\VerificationController@index', ['auth', 'admin']);
        $this->router->post('/admin/verifications/{id}/approve', 'Admin\\VerificationController@approve', ['auth', 'admin']);
        $this->router->post('/admin/verifications/{id}/reject', 'Admin\\VerificationController@reject', ['auth', 'admin']);

        $this->router->get('/admin/pricing', 'Admin\\PricingController@index', ['auth', 'admin']);
        $this->router->post('/admin/pricing', 'Admin\\PricingController@store', ['auth', 'admin']);
        $this->router->post('/admin/pricing/add', 'Admin\\PricingController@update', ['auth', 'admin']);
        $this->router->post('/admin/pricing/{id}/delete', 'Admin\\PricingController@delete', ['auth', 'admin']);

        $this->router->get('/admin/coupons', 'Admin\\CouponController@index', ['auth', 'admin']);
        $this->router->post('/admin/coupons', 'Admin\\CouponController@store', ['auth', 'admin']);
        $this->router->post('/admin/coupons/{id}/update', 'Admin\\CouponController@update', ['auth', 'admin']);
        $this->router->post('/admin/coupons/{id}/delete', 'Admin\\CouponController@delete', ['auth', 'admin']);

        $this->router->get('/admin/tickets', 'Admin\\TicketController@index', ['auth', 'admin']);
        $this->router->get('/admin/tickets/{id}', 'Admin\\TicketController@show', ['auth', 'admin']);
        $this->router->post('/admin/tickets/{id}/reply', 'Admin\\TicketController@reply', ['auth', 'admin']);
        $this->router->post('/admin/tickets/{id}/status', 'Admin\\TicketController@updateStatus', ['auth', 'admin']);

        $this->router->get('/admin/appearance/font', 'Admin\\AppearanceController@index', ['auth', 'admin']);
        $this->router->post('/admin/appearance/font', 'Admin\\AppearanceController@update', ['auth', 'admin']);

        $this->router->get('/admin/themes', 'Admin\\ThemeController@index', ['auth', 'admin']);
        $this->router->post('/admin/themes/upload', 'Admin\\ThemeController@upload', ['auth', 'admin']);
        $this->router->post('/admin/themes/activate-home', 'Admin\\ThemeController@activateHome', ['auth', 'admin']);
        $this->router->post('/admin/themes/activate-account', 'Admin\\ThemeController@activateAccount', ['auth', 'admin']);
        $this->router->post('/admin/themes/{slug}/delete', 'Admin\\ThemeController@delete', ['auth', 'admin']);

        $this->router->get('/admin/payment-gateways', 'Admin\\PaymentController@index', ['auth', 'admin']);
        $this->router->post('/admin/payment-gateways', 'Admin\\PaymentController@update', ['auth', 'admin']);

        $this->router->get('/admin/registrars', 'Admin\\RegistrarController@index', ['auth', 'admin']);
        $this->router->post('/admin/registrars', 'Admin\\RegistrarController@update', ['auth', 'admin']);

        $this->router->get('/admin/notifications', 'Admin\\NotificationController@index', ['auth', 'admin']);
        $this->router->post('/admin/notifications', 'Admin\\NotificationController@update', ['auth', 'admin']);

        $this->router->get('/admin/moderators', 'Admin\\ModeratorController@index', ['auth', 'admin']);
        $this->router->post('/admin/moderators', 'Admin\\ModeratorController@store', ['auth', 'admin']);
        $this->router->post('/admin/moderators/{id}/update', 'Admin\\ModeratorController@update', ['auth', 'admin']);
        $this->router->post('/admin/moderators/{id}/delete', 'Admin\\ModeratorController@delete', ['auth', 'admin']);

        $this->router->get('/admin/languages', 'Admin\\LanguageController@index', ['auth', 'admin']);
        $this->router->post('/admin/languages', 'Admin\\LanguageController@update', ['auth', 'admin']);

        $this->router->get('/admin/announcements', 'Admin\\AnnouncementController@index', ['auth', 'admin']);
        $this->router->post('/admin/announcements', 'Admin\\AnnouncementController@store', ['auth', 'admin']);
        $this->router->post('/admin/announcements/{id}/update', 'Admin\\AnnouncementController@update', ['auth', 'admin']);
        $this->router->post('/admin/announcements/{id}/delete', 'Admin\\AnnouncementController@delete', ['auth', 'admin']);

        $this->router->get('/admin/wallet', 'Admin\\WalletController@index', ['auth', 'admin']);
        $this->router->post('/admin/wallet/add-funds', 'Admin\\WalletController@addFunds', ['auth', 'admin']);

        $this->router->get('/admin/affiliates', 'Admin\\AffiliateController@index', ['auth', 'admin']);
        $this->router->post('/admin/affiliates/settings', 'Admin\\AffiliateController@updateSettings', ['auth', 'admin']);

        $this->router->get('/admin/email-templates', 'Admin\\EmailTemplateController@index', ['auth', 'admin']);
        $this->router->get('/admin/email-templates/{id}', 'Admin\\EmailTemplateController@edit', ['auth', 'admin']);
        $this->router->post('/admin/email-templates/{id}', 'Admin\\EmailTemplateController@update', ['auth', 'admin']);

        $this->router->get('/admin/seo', 'Admin\\SeoController@index', ['auth', 'admin']);
        $this->router->post('/admin/seo', 'Admin\\SeoController@update', ['auth', 'admin']);

        $this->router->get('/admin/settings', 'Admin\\SettingsController@index', ['auth', 'admin']);
        $this->router->post('/admin/settings', 'Admin\\SettingsController@update', ['auth', 'admin']);
    }
}
