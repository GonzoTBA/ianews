<?php

namespace App\Services;

use SimplePie;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class GoogleNewsService {
    public function getLatestNews($query = 'artificial intelligence') {
        $feed = new SimplePie();
        $feed->set_cache_location(storage_path('simplepie_cache'));
        $feed->set_feed_url("https://news.google.com/rss/search?q={$query}&hl=en-US&gl=US&ceid=US:en");
        $feed->init();
        $feed->handle_content_type();

        $client = new Client();
        $items = [];
        $counter = 0;

        foreach ($feed->get_items() as $item) {
            if ($counter >= 10) {
                break;
            }

            $title = $item->get_title();
            $link = $item->get_link();
            $summary = $this->fetchArticleContent($link);

            $items[] = [
                'title' => $title,
                'summary' => $summary,
                'source' => $link,
            ];

            $counter++;
        }

        return $items;
    }

    private function fetchArticleContent($url) {
        try {
            $client = new Client(['allow_redirects' =>
                [
                    'max'             => 10,       // máximo de redirecciones permitidas
                    'strict'          => true,     // utiliza la RFC 3986, sigue las RFCs 2616 y 2617
                    'referer'         => true,     // agrega la cabecera 'Referer' al redirigir
                    'protocols'       => ['http', 'https'],
                    'track_redirects' => true
                ],
            ]);
            $response = $client->request('GET', $url);
            // Obtén la URL final después de redirecciones
            $redirectHistory = $response->getHeader('X-Guzzle-Redirect-History');
            $effectiveUrl = end($redirectHistory) ?: $url;

            dd($redirectHistory, $effectiveUrl);
            // Realiza la solicitud final al URL efectivo
            $finalResponse = $this->client->request('GET', $effectiveUrl);
            $html = $finalResponse->getBody()->getContents();


            $crawler = new Crawler($html);

            // Extraer los párrafos dentro del contenido del artículo
            $content = $crawler->filter('article p')->each(function (Crawler $node, $i) {
                return $node->text();
            });

            if (empty($content)) {
                // Intenta otro selector si 'article p' no devuelve contenido
                $content = $crawler->filter('p')->each(function (Crawler $node, $i) {
                    return $node->text();
                });
            }

            // Unir los párrafos y limitar a una cantidad razonable de caracteres
            $content = implode(' ', $content);
            return substr($content, 0, 500) . '...';
        } catch (\Exception $e) {
            echo $e;
            return 'No se pudo extraer el contenido del artículo.';
        }
    }
}
