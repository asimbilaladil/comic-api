<?php

declare(strict_types=1);

namespace App\Service;

final class ProcessService
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
        return strtotime($b['date']) - strtotime($a['date']);
    }

    /**
     * Use to process the data after retrieving from httpservice.
     *
     * @return array
     */

    public function processData(): array
    {
        $webcomicNumber = $this->webcomicService->getLastestNumber();
        $webcomic       = $this->webcomicService->process($webcomicNumber);
        $pdlComics      = $this->pdlService->process();

        $allComics  = array_merge($webcomic, $pdlComics);
        usort($allComics, array($this,'sort'));
        return $allComics;
    }

}
