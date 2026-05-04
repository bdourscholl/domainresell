<div>
    <h3>Registrar Configuration</h3>
    <p>Registrar API settings are configured via environment variables (.env file).</p>
    <form action="/admin/registrars" method="POST">
        <?= csrf_field() ?>
        <div class="form-group">
            <label>Default Registrar</label>
            <select name="default_registrar" class="form-control">
                <option value="namecheap" <?= ($registrars['default'] ?? '') === 'namecheap' ? 'selected' : '' ?>>Namecheap</option>
                <option value="spaceship" <?= ($registrars['default'] ?? '') === 'spaceship' ? 'selected' : '' ?>>Spaceship</option>
                <option value="cloudflare" <?= ($registrars['default'] ?? '') === 'cloudflare' ? 'selected' : '' ?>>Cloudflare</option>
                <option value="mock" <?= ($registrars['default'] ?? '') === 'mock' ? 'selected' : '' ?>>Mock (Testing)</option>
            </select>
        </div>
        <button class="btn btn-primary">Save</button>
    </form>
</div>
