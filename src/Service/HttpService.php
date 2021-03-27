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
     * @param string $type
     *
     * @return array
     */
    public function getData(string $url): array
    {
        $response =  $this->client->request(
            'GET',
            $url
        );
        if($response->getStatusCode() == 200) {
            $contentType = $response->getHeaders()['content-type'][0];

            $data = ($contentType == 'application/json') ? $response->toArray(): $response->getContent() ;

            return [
                "data" => $data,
                "status" => true
            ];
        }
        return [
            "data" => [],
            "status" => false
        ];
    }
}
