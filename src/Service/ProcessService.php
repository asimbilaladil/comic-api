<?php


namespace App\Service;


class ProcessService
{

    private $httpService;

    public function __construct(HttpService $httpService)
    {
        $this->httpService = $httpService;
    }

    /**
     * Use to process the data after retrieving from httpservice.
     *
     * @return array
     */

    public function processData() :array
    {
        return $this->httpService->getData("");
    }
}
