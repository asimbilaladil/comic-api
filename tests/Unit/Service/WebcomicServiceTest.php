<?php


namespace App\Tests\Unit\Service;

use App\Service\HttpService;
use App\Service\WebcomicService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class WebcomicServiceTest extends TestCase
{
    public function testLastestWebcomicNumberSuccess(): void
    {
        $mockHttpService        = $this->createMock(HttpService::class);
        $parameterBagInterface  = $this->createMock(ParameterBagInterface::class);

        $webcomic['url']='https://xyz.com/';
        $webcomic['type']='xyz.json';
        $mockHttpService
            ->method('getData')
            ->willReturn([
                'status' => true,
                "data" => ["num" => 1]
            ]);
        $parameterBagInterface
            ->expects($this->once())
            ->method('get')
            ->willReturn($webcomic);

        $service = new WebcomicService(
            $mockHttpService,
            $parameterBagInterface
        );

        $result = $service->lastestWebcomicNumber();
        $this->assertEquals(1, $result);
    }

    public function testLastestWebcomicNumberFail(): void
    {
        $mockHttpService        = $this->createMock(HttpService::class);
        $parameterBagInterface  = $this->createMock(ParameterBagInterface::class);

        $webcomic['url']='https://xyz.com/';
        $webcomic['type']='xyz.json';
        $mockHttpService
            ->method('getData')
            ->willReturn([
                'status' => false,
                "data" => []
            ]);
        $parameterBagInterface
            ->expects($this->once())
            ->method('get')
            ->willReturn($webcomic);

        $service = new WebcomicService(
            $mockHttpService,
            $parameterBagInterface
        );

        $result = $service->lastestWebcomicNumber();
        $this->assertEquals(0, $result);
    }

    public function testWebcomicsSuccess(): void
    {
        $mockHttpService        = $this->createMock(HttpService::class);
        $parameterBagInterface  = $this->createMock(ParameterBagInterface::class);

        $webcomic['url']='https://xyz.com/';
        $webcomic['type']='xyz.json';
        $webcomic['limit']='1';
        $mockHttpService
            ->method('getData')
            ->willReturn([
                'status' => true,
                "data" => [
                    "month" => "01",
                    "num" => 1,
                    "year" => "2021",
                    "img" => "https://xyz.com/hello.png",
                    "title" => "test title",
                    "day" => "26"]
            ]);
        $parameterBagInterface
            ->expects($this->once())
            ->method('get')
            ->willReturn($webcomic);

        $service = new WebcomicService(
            $mockHttpService,
            $parameterBagInterface
        );

        $result = $service->webcomics(1);

        $this->assertEquals( [[
            "title" => "test title",
            "image" => "https://xyz.com/hello.png",
            "webUrl" => "https://xyz.com/0",
            "date" => "26-01-21",
            ]], $result);
    }

    public function testWebcomicsFail(): void
    {
        $mockHttpService        = $this->createMock(HttpService::class);
        $parameterBagInterface  = $this->createMock(ParameterBagInterface::class);

        $webcomic['url']='https://xyz.com/';
        $webcomic['type']='xyz.json';
        $webcomic['limit']='1';
        $mockHttpService
            ->method('getData')
            ->willReturn([
                'status' => false,
                "data" => []
            ]);
        $parameterBagInterface
            ->expects($this->once())
            ->method('get')
            ->willReturn($webcomic);

        $service = new WebcomicService(
            $mockHttpService,
            $parameterBagInterface
        );

        $result = $service->webcomics(1);

        $this->assertEquals( [], $result);
    }
}
