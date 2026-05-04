<div>
    <div class="toolbar">
        <?php foreach ($locales as $code => $name): ?>
        <a href="/admin/languages?locale=<?= e($code) ?>" class="btn btn-sm <?= ($selected_locale ?? 'en') === $code ? 'btn-primary' : '' ?>"><?= e($name) ?></a>
        <?php endforeach; ?>
    </div>
    <?php foreach ($translations as $group => $strings): ?>
    <div class="card">
        <h3><?= e(ucfirst($group)) ?></h3>
        <form action="/admin/languages" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="locale" value="<?= e($selected_locale ?? 'en') ?>">
            <input type="hidden" name="group" value="<?= e($group) ?>">
            <table class="table">
                <thead><tr><th>Key</th><th>Translation</th></tr></thead>
                <tbody>
                    <?php foreach ($strings as $key => $value): ?>
                    <tr>
                        <td><code><?= e($key) ?></code></td>
                        <td><input type="text" name="translations[<?= e($key) ?>]" class="form-control" value="<?= e($value) ?>"></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button class="btn btn-primary btn-sm">Save <?= e(ucfirst($group)) ?></button>
        </form>
    </div>
    <?php endforeach; ?>
</div>
