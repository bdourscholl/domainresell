<div class="checkout-page">
    <div class="container">
        <h2><?= __('cart.checkout') ?></h2>
        <div class="checkout-grid">
            <div class="checkout-summary">
                <h3><?= __('cart.order_summary') ?></h3>
                <?php foreach ($items as $item): ?>
                <div class="checkout-item">
                    <span><?= e($item['domain_name']) ?> (<?= ucfirst(e($item['item_type'])) ?>)</span>
                    <span><?= format_currency((float) $item['price']) ?></span>
                </div>
                <?php endforeach; ?>
                <div class="checkout-total">
                    <strong><?= __('cart.total') ?>: <?= format_currency($total ?? 0) ?></strong>
                </div>
            </div>

            <div class="checkout-payment">
                <h3><?= __('cart.payment_method') ?></h3>
                <form action="/checkout/process" method="POST">
                    <?= csrf_field() ?>
                    <div class="payment-options">
                        <?php foreach ($gateways as $key => $gateway): ?>
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="<?= e($key) ?>" <?= $key === 'manual' ? 'checked' : '' ?>>
                            <span><?= e($gateway['name'] ?? ucfirst($key)) ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg btn-block"><?= __('cart.place_order') ?></button>
                </form>
            </div>
        </div>
    </div>
</div>
