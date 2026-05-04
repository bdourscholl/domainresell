<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\View;
use App\Services\DomainSearchService;
use App\Services\HomepageService;

class HomeController
{
    public function index(Request $request): Response
    {
        $homepageService = new HomepageService();
        $data = $homepageService->getHomepageData();
        return View::renderWithLayout('home', $data, 'main');
    }

    public function search(Request $request): Response
    {
        $query = $request->get('q', '');
        $results = [];

        if ($query) {
            $searchService = new DomainSearchService();
            $results = $searchService->search($query);
        }

        return View::renderWithLayout('domain/search-results', [
            'query' => $query,
            'results' => $results,
        ], 'main');
    }

    public function searchAjax(Request $request): Response
    {
        $query = $request->get('q', '');
        if (!$query) {
            return Response::json(['results' => []]);
        }

        $searchService = new DomainSearchService();
        $results = $searchService->search($query);

        return Response::json(['results' => $results]);
    }

    public function setLocale(Request $request): Response
    {
        $locale = $request->param('locale') ?? $request->get('locale', 'en');
        if (!in_array($locale, ['en', 'bn'], true)) {
            $locale = 'en';
        }
        $session = new Session();
        $session->setLocale($locale);

        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        return Response::redirect($referer);
    }
}
