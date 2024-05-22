<?php

namespace App\Services;

use GuzzleHttp\Client;

class OpenAIService {
    private $client;
    private $apiKey;
    private $organizationId;

    public function __construct() {
        $this->client = new Client([
            'base_uri' => 'https://api.openai.com/v1/',
        ]);
        $this->apiKey = env('OPENAI_API_KEY');
        $this->organizationId = env('OPENAI_ORG_ID');
    }

    public function summarizeArticle($articleUrl) {
        try {
            $response = $this->client->request('POST', 'completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'OpenAI-Organization' => $this->organizationId, // Añade el Organization ID en los headers
                ],
                'json' => [
                    'model' => 'gpt-3.5-turbo',
                    'prompt' => "Summarize the following article: " . $articleUrl,
                    'max_tokens' => 100,
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            return $data['choices'][0]['text'] ?? 'No summary available.';

        } catch (\Exception $e) {
            return 'Error summarizing article: ' . $e->getMessage();
        }
    }
}