<?php


namespace App\Service;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class HttpService
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Gets the data from the url.
     *
     * @param string $url
     *
     * @return array
     */
    public function getData(string $url) :array
    {
        $response = $this->client->request(
            'GET',
            $url
        );

        $statusCode = $response->getStatusCode();
        $content = $response->toArray();

        return [
            'status'    => $statusCode,
            'data'      => $content
        ];
    }
}
