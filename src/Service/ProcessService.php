<?php


namespace App\Service;


use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ProcessService
{

    private $httpService;

    private $configParams;

    public function __construct(HttpService $httpService, ParameterBagInterface $configParams)
    {
        $this->httpService  = $httpService;
        $this->configParams = $configParams;
    }

    /**
     * Use to process the data after retrieving from httpservice.
     *
     * @return array
     */

    public function processData() :array
    {

        return $this->httpService->getData($this->configParams->get('webcomic'));
    }
}
