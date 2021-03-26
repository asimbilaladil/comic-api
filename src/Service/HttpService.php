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
    public function getData(string $url):array
    {
        $response = $this->client->request(
            'GET',
            $url
        );
        $contentType = $response->getHeaders()['content-type'][0];
        if($contentType == 'application/json'){
            $content = $response->toArray();
        } else{
            $xml = simplexml_load_string($response->getContent());
            $json = json_encode($xml);
            $json = json_decode($json,TRUE);
            $content = $json['channel']['item'];
        }
        return [
            'data'      => $content
        ];
    }
}
