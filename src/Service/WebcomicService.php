<?php


namespace App\Service;


use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class WebcomicService
{
    private $httpService;

    private $configParams;

    private $webcomic;

    public function __construct(HttpService $httpService, ParameterBagInterface $configParams)
    {
        $this->httpService  = $httpService;
        $this->configParams = $configParams;
        $this->webcomic     = $this->configParams->get('webcomic');
    }
    /**
     * Use to process latest webcomic to get the latest webcomic number
     *
     * @return int
     */

    public function lastestWebcomicNumber(): int
    {
        $url            = $this->webcomic['url'].$this->webcomic['type'];
        $response       = $this->httpService->getData($url);
        if($response['status']) {
            $currentComic   = $response['data'];
            return $currentComic['num'];
        }
        return 0;
    }

    /**
     * Use to process recent 10 comics from webcomic data
     *
     * @param int $comicNumber
     *
     * @return array
     */

    public function webcomics(int $comicNumber): array
    {
        $data           = [];
        for($i=$comicNumber-$this->webcomic['limit']; $i<$comicNumber; $i++ ){
            $url             = $this->webcomic['url'].$i.$this->webcomic['type'];
            $response        = $this->httpService->getData($url, 'json');
            if($response['status']){
                $webcomicData   = $response['data'];
                $date           = date_format(date_create($webcomicData['day'].'-'.$webcomicData['month'].'-'.$webcomicData['year']),'d-m-y');
                $data[]         = [
                    'title'  => $webcomicData['title'],
                    'image'  => $webcomicData['img'],
                    'webUrl' => $this->webcomic['url'].$i,
                    'date'   => $date
                ];
            }
        }

        return $data;
    }
}
