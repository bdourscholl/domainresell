<div class="account-page">
    <div class="container">
        <h2><?= __('general.tickets') ?></h2>
        <div class="card">
            <h3>Create New Ticket</h3>
            <form action="/account/tickets" method="POST">
                <?= csrf_field() ?>
                <div class="form-group">
                    <label>Subject</label>
                    <input type="text" name="subject" class="form-control" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Department</label>
                        <select name="department" class="form-control">
                            <option value="general">General</option>
                            <option value="billing">Billing</option>
                            <option value="technical">Technical</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Priority</label>
                        <select name="priority" class="form-control">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Message</label>
                    <textarea name="message" class="form-control" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit Ticket</button>
            </form>
        </div>
        <?php if (!empty($tickets)): ?>
        <h3>My Tickets</h3>
        <table class="table">
            <thead><tr><th>#</th><th>Subject</th><th>Status</th><th>Priority</th><th>Updated</th><th></th></tr></thead>
            <tbody>
                <?php foreach ($tickets as $t): ?>
                <tr>
                    <td><?= e($t['id']) ?></td>
                    <td><?= e($t['subject']) ?></td>
                    <td><span class="badge"><?= e($t['status']) ?></span></td>
                    <td><?= e($t['priority']) ?></td>
                    <td><?= e($t['updated_at']) ?></td>
                    <td><a href="/account/tickets/<?= $t['id'] ?>" class="btn btn-sm">View</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>
