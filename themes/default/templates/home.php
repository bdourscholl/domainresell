<?php
// Homepage — assembles all homepage sections
$sections = $sections ?? [];
$sectionMap = [];
foreach ($sections as $s) {
    $sectionMap[$s['section_key']] = $s;
}
?>

<?= partial('homepage-hero', ['hero' => $sectionMap['hero'] ?? []]) ?>

<?= partial('homepage-stats', ['stats' => $stats ?? []]) ?>

<?= partial('homepage-products') ?>

<?= partial('homepage-pricing', [
    'featured_tlds' => $featured_tlds ?? [],
    'pricing_section' => $sectionMap['pricing'] ?? [],
]) ?>

<?= partial('homepage-features', ['features_section' => $sectionMap['features'] ?? []]) ?>

<?= partial('homepage-faq', ['faq_section' => $sectionMap['faq'] ?? []]) ?>

<?= partial('homepage-testimonials', ['testimonials_section' => $sectionMap['testimonials'] ?? []]) ?>
