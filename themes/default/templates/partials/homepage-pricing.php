<?php if (!empty($featured_tlds)): ?>
<section class="theme-pricing pricing-section">
    <div class="container">
        <h2><?= e($pricing_section['section_title'] ?? 'Domain Pricing') ?></h2>
        <div class="pricing-table">
            <table>
                <thead><tr><th>TLD</th><th>Register</th><th>Renew</th><th>Transfer</th><th></th></tr></thead>
                <tbody>
                    <?php foreach ($featured_tlds as $tld): ?>
                    <tr>
                        <td><?= e($tld['tld']) ?></td>
                        <td><?= format_currency((float) $tld['register_price']) ?></td>
                        <td><?= format_currency((float) $tld['renew_price']) ?></td>
                        <td><?= format_currency((float) $tld['transfer_price']) ?></td>
                        <td><a href="/search?q=example<?= e($tld['tld']) ?>" class="btn btn-sm btn-primary">Search</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php endif; ?>
