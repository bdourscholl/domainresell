<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\App;

class EmailTemplate
{
    public static function find(int $id): ?array
    {
        return App::getInstance()->getDb()->queryOne("SELECT * FROM email_templates WHERE id = ?", [$id]);
    }

    public static function findBySlug(string $slug): ?array
    {
        return App::getInstance()->getDb()->queryOne("SELECT * FROM email_templates WHERE slug = ? AND is_active = 1", [$slug]);
    }

    public static function all(): array
    {
        return App::getInstance()->getDb()->query("SELECT * FROM email_templates ORDER BY name ASC");
    }

    public static function update(int $id, array $data): int
    {
        return App::getInstance()->getDb()->update('email_templates', $data, 'id = ?', [$id]);
    }

    public static function render(string $slug, array $variables): ?array
    {
        $template = self::findBySlug($slug);
        if (!$template) return null;

        $subject = $template['subject'];
        $body = $template['body_html'];

        foreach ($variables as $key => $value) {
            $subject = str_replace('{{' . $key . '}}', (string) $value, $subject);
            $body = str_replace('{{' . $key . '}}', (string) $value, $body);
        }

        return ['subject' => $subject, 'body' => $body];
    }
}
