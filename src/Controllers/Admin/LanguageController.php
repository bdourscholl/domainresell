<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;

class LanguageController
{
    public function index(Request $request): Response
    {
        $locales = ['en' => 'English', 'bn' => 'Bengali'];
        $selectedLocale = $request->get('locale', 'en');
        $translations = [];

        $langPath = BASE_PATH . '/resources/lang/' . $selectedLocale;
        if (is_dir($langPath)) {
            foreach (glob($langPath . '/*.php') as $file) {
                $group = basename($file, '.php');
                $translations[$group] = require $file;
            }
        }

        return View::renderWithLayout('admin/languages', [
            'locales' => $locales,
            'selected_locale' => $selectedLocale,
            'translations' => $translations,
        ], 'admin');
    }

    public function update(Request $request): Response
    {
        $locale = $request->post('locale', 'en');
        $group = $request->post('group', '');
        $translations = $request->post('translations', []);

        if ($group && is_array($translations)) {
            $langFile = BASE_PATH . '/resources/lang/' . $locale . '/' . $group . '.php';
            $existing = file_exists($langFile) ? require $langFile : [];
            $merged = array_merge($existing, $translations);

            $content = "<?php\n\nreturn " . var_export($merged, true) . ";\n";
            file_put_contents($langFile, $content);
        }

        flash('success', 'Translations updated.');
        return Response::redirect("/admin/languages?locale={$locale}");
    }
}
