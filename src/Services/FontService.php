<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\FontSetting;

class FontService
{
    public function getActiveFont(): array
    {
        $font = FontSetting::getActive();
        return $font ?: [
            'font_name' => 'Tiro Bangla',
            'font_family' => "'Tiro Bangla', serif",
            'font_type' => 'local',
            'font_url' => null,
        ];
    }

    public function activate(int $fontId): void
    {
        FontSetting::activate($fontId);
    }

    public function getAll(): array
    {
        return FontSetting::all();
    }
}
