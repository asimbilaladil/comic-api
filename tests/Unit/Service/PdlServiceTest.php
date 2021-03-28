<?php


namespace App\Tests\Unit\Service;

use App\Service\HttpService;
use App\Service\PdlService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


class PdlServiceTest extends TestCase
{
    public function testLastestWebcomicNumberSuccess(): void
    {
        $mockHttpService        = $this->createMock(HttpService::class);
        $parameterBagInterface  = $this->createMock(ParameterBagInterface::class);

        $pdl['url']='https://xyz.com/';

        $mockHttpService
            ->method('getData')
            ->willReturn([
                'status' => true,
                "data" => '<?xml version="1.0" encoding="UTF-8"?> <element> <channel> <item> <element> <guid>https://xyz.com/test.png</guid> <link>http://https://xyz.com/</link> <pubDate>Thu, 25 Mar 2021 15:29:06 +0000</pubDate> <title>Test Title</title> </element> </item> </channel> </element>'
            ]);
        $parameterBagInterface
            ->expects($this->once())
            ->method('get')
            ->willReturn($pdl);

        $service = new PdlService(
            $mockHttpService,
            $parameterBagInterface
        );

        $result = $service->comics();
        $this->assertEquals([ [
            "title" => "Test Title",
            "image" => "http://https://xyz.com/",
            "webUrl" => "https://xyz.com/test.png",
            "date" => "25-03-21"
        ]], $result);
    }

    public function testLastestWebcomicNumberFail(): void
    {
        $mockHttpService        = $this->createMock(HttpService::class);
        $parameterBagInterface  = $this->createMock(ParameterBagInterface::class);

        $pdl['url']='https://xyz.com/';

        $mockHttpService
            ->method('getData')
            ->willReturn([
                'status' => false,
                "data" => ''
            ]);
        $parameterBagInterface
            ->expects($this->once())
            ->method('get')
            ->willReturn($pdl);

        $service = new PdlService(
            $mockHttpService,
            $parameterBagInterface
        );

        $result = $service->comics();
        $this->assertEquals([], $result);
    }

}
