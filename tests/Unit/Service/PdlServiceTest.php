<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Service\HttpService;
use App\Service\PdlService;
use Generator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


final class PdlServiceTest extends TestCase
{
    private $httpService;

    private $parameterBagInterface;

    private $pdl;

    public function setUp() :void
    {
        $this->httpService              = $this->createMock(HttpService::class);
        $this->parameterBagInterface    = $this->createMock(ParameterBagInterface::class);
        $this->pdl                 = [
            'url'   => 'https://xyz.com/',
        ];
    }

    public function invokeMethod(&$object, string $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    public function testBuildDate(): void
    {
        $this->parameterBagInterface
            ->expects($this->once())
            ->method('get')
            ->willReturn($this->pdl);

        $service = new PdlService(
            $this->httpService,
            $this->parameterBagInterface
        );
        $publishdate    = 'Thu, 25 Mar 2021 15:29:06 +0000';
        $expected       = '25-03-2021';
        $result         = $this->invokeMethod($service, 'buildDate', [$publishdate]);
        $this->assertEquals($expected, $result);

    }

    public function testBuildArray(): void
    {
        $this->parameterBagInterface
            ->expects($this->once())
            ->method('get')
            ->willReturn($this->pdl);

        $service = new PdlService(
            $this->httpService,
            $this->parameterBagInterface
        );
        $xml        = '<?xml version="1.0" encoding="UTF-8"?> <element> <channel> <item>  <guid>https://xyz.com/test.png</guid> <link>https://xyz.com/</link> <pubDate>Thu, 25 Mar 2021 15:29:06 +0000</pubDate> <title>Test Title</title>  </item> </channel> </element>';
        $expected   =   [
            "title" => "Test Title",
            "guid" => "https://xyz.com/test.png",
            "link" => "https://xyz.com/",
            "pubDate" =>  "Thu, 25 Mar 2021 15:29:06 +0000",
        ];
        $result = $this->invokeMethod($service, 'buildArray', [$xml]);
        $this->assertEquals($expected, $result);

    }

    public function testBuildData(): void
    {
        $this->parameterBagInterface
            ->expects($this->once())
            ->method('get')
            ->willReturn($this->pdl);

        $service = new PdlService(
            $this->httpService,
            $this->parameterBagInterface
        );
        $data = [

            "title" => "Test Title",
            "guid" => "https://xyz.com/",
            "link" => "https://xyz.com/test.png",
            "pubDate" =>  "Thu, 25 Mar 2021 15:29:06 +0000",

        ];
        $expected = [
            'title'  => "Test Title",
            'webUrl'  => "https://xyz.com/",
            'image' => "https://xyz.com/test.png",
            'date'   => "25-03-2021"
        ];
        $result = $this->invokeMethod($service, 'buildData', [$data]);

        $this->assertEquals($expected, $result);

    }

    public function testProcessSuccess(): void
    {
        $this->httpService
            ->method('getData')
            ->willReturn([
                'status' => true,
                "data" => '<?xml version="1.0" encoding="UTF-8"?> <element> <channel> <item> <element> <guid>https://xyz.com/test.png</guid> <link>http://https://xyz.com/</link> <pubDate>Thu, 25 Mar 2021 15:29:06 +0000</pubDate> <title>Test Title</title> </element> </item> </channel> </element>'
            ]);
        $this->parameterBagInterface
            ->expects($this->once())
            ->method('get')
            ->willReturn($this->pdl);

        $service = new PdlService(
            $this->httpService,
            $this->parameterBagInterface
        );

        $result = $service->process();
        $this->assertEquals([ [
            "title" => "Test Title",
            "image" => "http://https://xyz.com/",
            "webUrl" => "https://xyz.com/test.png",
            "date" => "25-03-2021"
        ]], $result);
    }

    public function testProcessFail(): void
    {
        $this->httpService
            ->method('getData')
            ->willReturn([
                'status' => false,
                "data" => ''
            ]);
        $this->parameterBagInterface
            ->expects($this->once())
            ->method('get')
            ->willReturn($this->pdl);

        $service = new PdlService(
            $this->httpService,
            $this->parameterBagInterface
        );

        $result = $service->process();
        $this->assertEquals([], $result);
    }

}
