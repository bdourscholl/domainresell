<div class="account-page">
    <div class="container">
        <h2>Ticket: <?= e($ticket['subject'] ?? '') ?></h2>
        <div class="ticket-meta">
            <span class="badge"><?= e($ticket['status'] ?? '') ?></span>
            <span>Priority: <?= e($ticket['priority'] ?? '') ?></span>
        </div>
        <div class="ticket-replies">
            <?php foreach ($replies as $reply): ?>
            <div class="ticket-reply <?= ($reply['is_staff'] ?? false) ? 'staff-reply' : 'user-reply' ?>">
                <div class="reply-header">
                    <strong><?= ($reply['is_staff'] ?? false) ? 'Staff' : 'You' ?></strong>
                    <span><?= e($reply['created_at'] ?? '') ?></span>
                </div>
                <div class="reply-body"><?= nl2br(e($reply['message'])) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php if (($ticket['status'] ?? '') !== 'closed'): ?>
        <div class="card">
            <form action="/account/tickets/<?= e($ticket['id']) ?>/reply" method="POST">
                <?= csrf_field() ?>
                <div class="form-group">
                    <label>Your Reply</label>
                    <textarea name="message" class="form-control" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Send Reply</button>
            </form>
        </div>
        <?php endif; ?>
    </div>
</div>
