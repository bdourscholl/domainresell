<div>
    <h3>Payment Gateway Configuration</h3>
    <p>Payment gateway settings are configured via environment variables (.env file). Here you can enable/disable gateways.</p>
    <table class="table">
        <thead><tr><th>Gateway</th><th>Status</th></tr></thead>
        <tbody>
            <?php
            $gatewayNames = ['stripe' => 'Stripe', 'paypal' => 'PayPal', 'razorpay' => 'Razorpay', 'sslcommerz' => 'SSLCommerz', 'bkash' => 'bKash', 'nagad' => 'Nagad', 'manual' => 'Manual/Bank'];
            foreach ($gatewayNames as $key => $name):
            ?>
            <tr>
                <td><?= e($name) ?></td>
                <td><?= ($gateways[$key]['enabled'] ?? false) ? '<span class="badge badge-success">Enabled</span>' : '<span class="badge badge-danger">Disabled</span>' ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
