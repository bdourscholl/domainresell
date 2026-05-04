<div class="search-results-page">
    <div class="container">
        <h2><?= __('domain.search_results') ?></h2>
        <form action="/search" method="GET" class="domain-search-form">
            <input type="text" name="q" class="search-input" value="<?= e($query ?? '') ?>" placeholder="<?= __('domain.search_placeholder') ?>">
            <button type="submit" class="btn btn-primary"><?= __('domain.search_button') ?></button>
        </form>

        <?php if (!empty($query)): ?>
        <div class="results-list" id="searchResults">
            <?php if (empty($results)): ?>
                <p class="no-results"><?= __('domain.no_results') ?></p>
            <?php else: ?>
                <?php foreach ($results as $result): ?>
                <div class="result-row <?= $result['available'] ? 'available' : 'unavailable' ?>">
                    <div class="result-domain">
                        <span class="domain-name"><?= e($result['domain']) ?></span>
                        <span class="status-badge <?= $result['available'] ? 'badge-success' : 'badge-danger' ?>">
                            <?= $result['available'] ? __('domain.available') : __('domain.taken') ?>
                        </span>
                    </div>
                    <div class="result-price">
                        <?php if ($result['available']): ?>
                            <span class="price"><?= format_currency($result['price']) ?>/yr</span>
                            <form action="/cart/add" method="POST" class="inline-form">
                                <?= csrf_field() ?>
                                <input type="hidden" name="domain" value="<?= e($result['sld']) ?>">
                                <input type="hidden" name="tld" value="<?= e($result['tld']) ?>">
                                <input type="hidden" name="type" value="register">
                                <input type="hidden" name="years" value="1">
                                <button type="submit" class="btn btn-primary btn-sm"><?= __('cart.add_to_cart') ?></button>
                            </form>
                        <?php else: ?>
                            <span class="price-muted"><?= __('domain.not_available') ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
