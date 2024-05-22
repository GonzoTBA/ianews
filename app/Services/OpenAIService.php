<?php

namespace App\Services;

use GuzzleHttp\Client;

class OpenAIService {
    private $client;
    private $apiKey;
    private $organizationId;

    public function __construct() {
        $this->client = new Client([
            'base_uri' => 'https://api.openai.com/v1/chat/',
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
                    // 'prompt' => "Summarize the following article: " . $articleUrl,
                    'messages' => [
                        ['role' => "user",
                        'content' => "Summarize, in spanish and in 100 words, the following article: " . $articleUrl]
                    ],
                    'max_tokens' => 150,
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            return $data['choices'][0]['message']['content'] ?? 'No summary available.';

        } catch (\Exception $e) {
            dd($e->getMessage());
            return 'Error summarizing article: ' . $e->getMessage();
        }
    }
}
