<?php


namespace App\Service;


use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Serializer\Encoder\XmlEncoder;

class ProcessService
{

    private $pdlService;

    private $webcomicService;

    public function __construct(PdlService $pdlService, WebcomicService $webcomicService)
    {
        $this->pdlService       = $pdlService;
        $this->webcomicService  = $webcomicService;
    }

    /**
     * Use to sort two arrays by date
     *
     * @return array
     */

    private static function sort($a, $b): int
    {
        return strcmp($a['date'], $b['date']);
    }

    /**
     * Use to process the data after retrieving from httpservice.
     *
     * @return array
     */

    public function processData(): array
    {
        $webcomicNumber = $this->webcomicService->lastestWebcomicNumber();
        $webcomic       = $this->webcomicService->webcomics($webcomicNumber);
        $pdlComics      = $this->pdlService->comics();

        $allComics  = array_merge($webcomic, $pdlComics);
        usort($allComics, array($this,'sort'));
        return $allComics;
    }

}
