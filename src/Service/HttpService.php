<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class HttpService
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getData(string $url): array
    {
        $response = $this->getResponse('GET', $url);

        $result = [
            'data'      => [],
            'status'    => false
        ];
        if($response->getStatusCode() === 200) {
            $contentType = $response->getHeaders()['content-type'][0];

            $data = ($contentType === 'application/json') ? $response->toArray(): $response->getContent();

            $result = [
                "data"      => $data,
                "status"    => true
            ];
        }
        return $result;
    }

    private function getResponse(string $method, string $url): ResponseInterface
    {
        return  $this->client->request($method, $url);
    }
}
