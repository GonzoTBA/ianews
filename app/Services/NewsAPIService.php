<?php

namespace App\Services;

use GuzzleHttp\Client;

class NewsAPIService {
    private $client;
    private $apiKey;

    public function __construct() {
        $this->client = new Client([
            'base_uri' => 'https://newsapi.org/v2/',
        ]);
        $this->apiKey = env('NEWSAPI_KEY');
    }

    public function fetchTopHeadlines($query) {
        try {
            $response = $this->client->request('GET', 'everything', [
                'query' => [
                    'q' => $query,
                    'apiKey' => $this->apiKey,
                    'language' => 'en',
                    'pageSize' => 3,
                    'sortBy' => 'publishedAt',
                ]
            ]);

            $body = $response->getBody()->getContents();
            $data = json_decode($body, true);

            return $data['articles'];

        } catch (\Exception $e) {
            return 'Error fetching news: ' . $e->getMessage();
        }
    }
}
