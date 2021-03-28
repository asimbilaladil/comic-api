<?php

declare(strict_types=1);

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

    public function getLastestNumber(): int
    {
        $url            = $this->buildUrl(null , $this->webcomic['type']);
        $result         = $this->httpService->getData($url);
        if($result['status']) {
            $currentComic   = $result['data'];
            return $currentComic['num'];
        }
        return -1;
    }

    public function process(int $comicNumber): array
    {
        $data           = [];
        for($i=$comicNumber-$this->webcomic['limit']; $i<$comicNumber; $i++ ){
            $url        = $this->buildUrl($i, $this->webcomic['type']);
            $result     = $this->httpService->getData($url);
            if($result['status']){
                $data [] = $this->buildData($result['data'], $i);
            }
        }

        return $data;
    }

    private function buildData(array $data, int $num): array
    {
        return  [
            'title'  => $data['title'],
            'image'  => $data['img'],
            'webUrl' => $this->buildUrl($num, null),
            'date'   => $this->buildDate($data['day'], $data['month'], $data['year'])
        ];
    }

    private function buildUrl(?int $num,  ?string $type): string
    {
        if($num === null){
            $result = sprintf('%s%s', $this->webcomic['url'], $type);
        } elseif ($type === null){
            $result = sprintf('%s%d', $this->webcomic['url'], $num);
        } else {
            $result = sprintf('%s%d%s', $this->webcomic['url'], $num , $type);
        }
        return $result;
    }

    private function buildDate(string $day, string $month, string $year): string
    {
        return date_format(date_create($day.'-'.$month.'-'.$year),'d-m-Y');
    }
}
