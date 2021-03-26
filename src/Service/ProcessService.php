<?php


namespace App\Service;


use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Serializer\Encoder\XmlEncoder;

class ProcessService
{

    private $httpService;

    private $configParams;

    public function __construct(HttpService $httpService, ParameterBagInterface $configParams)
    {
        $this->httpService  = $httpService;
        $this->configParams = $configParams;
    }


    private static function cmp($a, $b) {
        return strcmp($a['date'], $b['date']);
    }

    /**
     * Use to process the data after retrieving from httpservice.
     *
     * @return array
     */

    public function processData() :array
    {
        $webcomic   = $this->processWebcomicData();
        $pdlComics  = $this->processPdlData();

        $allComics  = array_merge($webcomic, $pdlComics);
        usort($allComics, array($this,'cmp'));
        return $allComics;
    }

    private function processWebcomicData() :array
    {

        $webcomic       = $this->configParams->get('webcomic');
        $url            = $webcomic['url'].$webcomic['type'];
        $currentComic   = $this->httpService->getData($url);
        $comicNumber    = $currentComic['data']['num'];

        for($i=$comicNumber-10; $i<=$comicNumber; $i++ ){
           $url             = $webcomic['url'].$i.$webcomic['type'];
           $webcomicData    = $this->httpService->getData($url);
           $webcomicData    = $webcomicData['data'];
           $date            = date_format(date_create($webcomicData['day'].'-'.$webcomicData['month'].'-'.$webcomicData['year']),'d-m-y');
           $data[]        = [
               'title'  => $webcomicData['title'],
               'image'  => $webcomicData['img'],
               'webUrl' => $webcomic['url'].$i,
               'date'   => $date
           ];
        }
        return $data;
    }

    private function processPdlData() :array
    {
        $pdlComics = $this->httpService->getData($this->configParams->get('pdl'));
        foreach ($pdlComics['data'] as $pdlComic){
            $date           = date_format(date_create($pdlComic['pubDate']),'d-m-y');
            $data[]    = [
                'title'  => $pdlComic['title'],
                'image'  => '',
                'webUrl' => $pdlComic['guid'],
                'date'   => $date
            ];
        }
        return $data;
    }
}
