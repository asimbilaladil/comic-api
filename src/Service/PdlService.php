<?php


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

    /**
     * Use to process recent 10 comics from pdl data
     *
     * @return array
     */

    public function comics(): array
    {
        $response   = $this->httpService->getData($this->pdl['url'], 'xml');
        $data = [];
        if($response['status']){
            $xml        = simplexml_load_string($response['data']);
            $json       = json_decode(json_encode($xml),true);
            $pdlComics  = $json['channel']['item'];
            foreach ($pdlComics as $pdlComic){
                $date   = date_format(date_create($pdlComic['pubDate']),'d-m-y');
                $data[] = [
                    'title'  => $pdlComic['title'],
                    'image'  => $pdlComic['link'],
                    'webUrl' => $pdlComic['guid'],
                    'date'   => $date
                ];
            }
        }
        return $data;
    }

}
