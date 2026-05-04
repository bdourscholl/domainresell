<section class="starter-hero">
    <div class="container">
        <h1>Domain Search</h1>
        <form action="/search" method="GET" class="domain-search-form">
            <input type="text" name="q" placeholder="Find your domain..." class="search-input" required>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>
</section>
<?php if (!empty($featured_tlds)): ?>
<section class="starter-pricing">
    <div class="container">
        <h2>Pricing</h2>
        <div class="pricing-table">
            <table>
                <thead><tr><th>TLD</th><th>Price</th><th></th></tr></thead>
                <tbody>
                    <?php foreach ($featured_tlds as $tld): ?>
                    <tr><td><?= e($tld['tld']) ?></td><td><?= format_currency((float) $tld['register_price']) ?>/yr</td><td><a href="/search" class="btn btn-sm btn-primary">Get</a></td></tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php endif; ?>
