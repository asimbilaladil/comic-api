<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PdlService
{
    private $httpService;

    private $configParams;

    private $pdl;

    public function __construct(HttpService $httpService, ParameterBagInterface $configParams)
    {
        $this->httpService  = $httpService;
        $this->configParams = $configParams;
        $this->pdl          = $this->configParams->get('pdl');
    }

    public function process(): array
    {
        $response   = $this->httpService->getData($this->pdl['url']);
        $data       = [];
        if($response['status']){
            $pdlComics = $this->buildArray($response['data']);
            foreach ($pdlComics as $pdlComic){
                $data[] = $this->buildData($pdlComic);
            }
        }
        return $data;
    }

    private function buildData(array $data): array
    {
        return  [
            'title'  => $data['title'],
            'image'  => $data['link'],
            'webUrl' => $data['guid'],
            'date'   => $this->buildDate($data['pubDate'])
        ];
    }

    private function buildArray(string $data): array
    {
        $xml        = simplexml_load_string($data);
        $json       = json_decode(json_encode($xml),true);
        return  $json['channel']['item'];
    }

    private function buildDate($publishDate): string{

        return date_format(date_create($publishDate),'d-m-Y');
    }

}
