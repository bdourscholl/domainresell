<div>
    <h3>Notification Channels</h3>
    <form action="/admin/notifications" method="POST">
        <?= csrf_field() ?>
        <div class="form-group"><label><input type="checkbox" name="telegram_enabled" value="1" <?= setting('telegram_enabled', '0') === '1' ? 'checked' : '' ?>> Telegram Bot</label></div>
        <div class="form-group"><label><input type="checkbox" name="whatsapp_enabled" value="1" <?= setting('whatsapp_enabled', '0') === '1' ? 'checked' : '' ?>> WhatsApp Business API</label></div>
        <div class="form-group"><label><input type="checkbox" name="email_enabled" value="1" <?= setting('email_enabled', '1') === '1' ? 'checked' : '' ?>> Email Notifications</label></div>
        <button class="btn btn-primary">Save Settings</button>
    </form>
    <h3>Recent Notifications</h3>
    <table class="table">
        <thead><tr><th>Channel</th><th>Message</th><th>Status</th><th>Date</th></tr></thead>
        <tbody>
            <?php foreach ($logs as $l): ?>
            <tr>
                <td><?= e($l['channel']) ?></td>
                <td><?= e(substr($l['message'], 0, 100)) ?></td>
                <td><span class="badge"><?= e($l['status']) ?></span></td>
                <td><?= e($l['created_at']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
