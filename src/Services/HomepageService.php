<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\HomepageStat;
use App\Models\HomepageSection;
use App\Models\TldPricing;
use App\Models\Announcement;

class HomepageService
{
    public function getHomepageData(): array
    {
        return [
            'stats' => HomepageStat::getActive(),
            'sections' => HomepageSection::getActive(),
            'featured_tlds' => TldPricing::getFeatured(),
            'announcements' => Announcement::getHomepageAnnouncements(),
        ];
    }

    public function updateStat(int $id, array $data): void
    {
        HomepageStat::update($id, $data);
    }

    public function updateSection(int $id, array $data): void
    {
        HomepageSection::update($id, $data);
    }
}
