<div class="cart-page">
    <div class="container">
        <h2><?= __('cart.title') ?></h2>
        <?php if (empty($items)): ?>
            <div class="empty-state">
                <i class="fas fa-shopping-cart fa-3x"></i>
                <p><?= __('cart.empty') ?></p>
                <a href="/search" class="btn btn-primary"><?= __('cart.search_domains') ?></a>
            </div>
        <?php else: ?>
            <div class="cart-items">
                <table class="table">
                    <thead>
                        <tr>
                            <th><?= __('cart.domain') ?></th>
                            <th><?= __('cart.type') ?></th>
                            <th><?= __('cart.years') ?></th>
                            <th><?= __('cart.price') ?></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= e($item['domain_name']) ?></td>
                            <td><?= ucfirst(e($item['item_type'])) ?></td>
                            <td><?= e($item['years']) ?></td>
                            <td><?= format_currency((float) $item['price']) ?></td>
                            <td>
                                <form action="/cart/remove/<?= e($item['id']) ?>" method="POST" class="inline-form">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="cart-coupon">
                <form action="/cart/coupon" method="POST" class="inline-form">
                    <?= csrf_field() ?>
                    <input type="text" name="coupon_code" class="form-control" placeholder="<?= __('cart.coupon_code') ?>">
                    <button type="submit" class="btn btn-secondary"><?= __('cart.apply') ?></button>
                </form>
            </div>

            <div class="cart-total">
                <h3><?= __('cart.total') ?>: <?= format_currency($total ?? 0) ?></h3>
                <a href="/checkout" class="btn btn-primary btn-lg"><?= __('cart.proceed_checkout') ?></a>
            </div>
        <?php endif; ?>
    </div>
</div>
