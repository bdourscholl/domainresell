<div>
    <h3><?= e($ticket['subject'] ?? '') ?></h3>
    <div class="ticket-meta">
        <span>Customer: <?= e($ticket['user_name'] ?? '-') ?></span>
        <span class="badge"><?= e($ticket['status'] ?? '') ?></span>
        <span>Priority: <?= e($ticket['priority'] ?? '') ?></span>
        <form action="/admin/tickets/<?= $ticket['id'] ?>/status" method="POST" class="inline-form">
            <?= csrf_field() ?>
            <select name="status" class="form-control"><option value="open">Open</option><option value="answered">Answered</option><option value="closed">Closed</option></select>
            <button class="btn btn-sm btn-primary">Update</button>
        </form>
    </div>
    <div class="ticket-replies">
        <?php foreach ($replies as $r): ?>
        <div class="ticket-reply <?= ($r['is_staff'] ?? false) ? 'staff-reply' : 'user-reply' ?>">
            <div class="reply-header"><strong><?= ($r['is_staff'] ?? false) ? 'Staff' : 'Customer' ?></strong> <span><?= e($r['created_at']) ?></span></div>
            <div class="reply-body"><?= nl2br(e($r['message'])) ?></div>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="card">
        <form action="/admin/tickets/<?= $ticket['id'] ?>/reply" method="POST">
            <?= csrf_field() ?>
            <div class="form-group"><textarea name="message" class="form-control" rows="3" required></textarea></div>
            <button class="btn btn-primary">Send Reply</button>
        </form>
    </div>
</div>
