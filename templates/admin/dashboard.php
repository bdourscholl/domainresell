<div class="admin-dashboard">
    <div class="dashboard-stats-grid">
        <div class="stat-card stat-primary"><div class="stat-icon"><i class="fas fa-users"></i></div><div class="stat-info"><h4><?= $total_customers ?? 0 ?></h4><p>Customers</p></div></div>
        <div class="stat-card stat-success"><div class="stat-icon"><i class="fas fa-globe"></i></div><div class="stat-info"><h4><?= $total_domains ?? 0 ?></h4><p>Domains</p></div></div>
        <div class="stat-card stat-warning"><div class="stat-icon"><i class="fas fa-shopping-bag"></i></div><div class="stat-info"><h4><?= $total_orders ?? 0 ?></h4><p>Orders</p></div></div>
        <div class="stat-card stat-info"><div class="stat-icon"><i class="fas fa-dollar-sign"></i></div><div class="stat-info"><h4><?= format_currency($total_revenue ?? 0) ?></h4><p>Revenue</p></div></div>
        <div class="stat-card stat-danger"><div class="stat-icon"><i class="fas fa-headset"></i></div><div class="stat-info"><h4><?= $open_tickets ?? 0 ?></h4><p>Open Tickets</p></div></div>
    </div>
    <div class="section">
        <h3>Recent Orders</h3>
        <table class="table">
            <thead><tr><th>Order #</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th></tr></thead>
            <tbody>
                <?php foreach ($recent_orders as $o): ?>
                <tr>
                    <td><a href="/admin/orders/<?= $o['id'] ?>"><?= e($o['order_number']) ?></a></td>
                    <td><?= e($o['user_name'] ?? '-') ?></td>
                    <td><?= format_currency((float) $o['total']) ?></td>
                    <td><span class="badge"><?= e($o['status']) ?></span></td>
                    <td><?= e($o['created_at']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
