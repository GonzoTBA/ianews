<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NewsAPIService;
use App\Services\OpenAIService;

class NewsController extends Controller
{
    private $openAIService;

    public function __construct(OpenAIService $openAIService) {
        $this->openAIService = $openAIService;
    }

    public function index() {
        $newsService = new NewsAPIService();
        $articles = $newsService->fetchTopHeadlines('artificial intelligence');

        // Procesar cada artículo para generar resúmenes
        foreach ($articles as &$article) {
            $article['summary'] = $this->openAIService->summarizeArticle($article['url']);
        }

        return view('news.index', ['news' => $articles]);
    }
}
