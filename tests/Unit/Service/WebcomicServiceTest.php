<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Service\HttpService;
use App\Service\WebcomicService;
use Generator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


final class WebcomicServiceTest extends TestCase
{
    private $httpService;

    private $parameterBagInterface;

    private $webcomic;

    public function setUp() :void
    {
        $this->httpService              = $this->createMock(HttpService::class);
        $this->parameterBagInterface    = $this->createMock(ParameterBagInterface::class);
        $this->webcomic                 = [
            'url'   => 'https://xyz.com/',
            'type'  =>  'xyz.json',
            'limit' => 1
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
            ->willReturn($this->webcomic);

        $service = new WebcomicService(
            $this->httpService,
            $this->parameterBagInterface
        );
        $day    = '10';
        $month  = '11';
        $year   = '2010';
        $result = $this->invokeMethod($service, 'buildDate', [$day, $month, $year]);
        $this->assertEquals('10-11-2010', $result);

    }

    public function testBuildData(): void
    {
        $this->parameterBagInterface
            ->expects($this->once())
            ->method('get')
            ->willReturn($this->webcomic);

        $service = new WebcomicService(
            $this->httpService,
            $this->parameterBagInterface
        );
        $num = 1;
        $data = [
            'title' => 'hello',
            'img'   => 'test.png',
            'day'   => '11',
            'month' => '10',
            'year'   => '2021'
        ];
        $expected = [
            'title'  => 'hello',
            'image'  => 'test.png',
            'webUrl' => 'https://xyz.com/1',
            'date'   => '11-10-2021'
        ];
        $result = $this->invokeMethod($service, 'buildData', [$data, $num]);

        $this->assertEquals($expected, $result);

    }

    public function buildUrlDataProvider(): Generator
    {
        yield 'num is null' => [
            null, '/xy.json', 'https://xyz.com//xy.json'
        ];
        yield 'type is null' => [
            1, null, 'https://xyz.com/1'
        ];

        yield 'num and type is not null' => [
            1, '/xy.json', 'https://xyz.com/1/xy.json'
        ];
    }

    /**
     * @dataProvider buildUrlDataProvider
     */

    public function testBuildUrl(?int $num,  ?string $type, string $expected): void
    {
        $this->parameterBagInterface
            ->expects($this->once())
            ->method('get')
            ->willReturn($this->webcomic);

        $service = new WebcomicService(
            $this->httpService,
            $this->parameterBagInterface
        );

        $result = $this->invokeMethod($service, 'buildUrl', [$num, $type]);
        $this->assertEquals($expected, $result);

    }

    public function lastestNumberDataProvider(): Generator
    {
        yield 'success' => [[
            'status' => true,
            "data" => ["num" => 1]
        ], 1];

        yield 'fail' => [[
            'status' => false,
            "data" => []
        ],-1];
    }

    /**
     * @dataProvider lastestNumberDataProvider
     */

    public function testLastestNumber(array $data, int $expected): void
    {
        $this->httpService
            ->method('getData')
            ->willReturn($data);
        $this->parameterBagInterface
            ->expects($this->once())
            ->method('get')
            ->willReturn($this->webcomic);

        $service = new WebcomicService(
            $this->httpService,
            $this->parameterBagInterface
        );

        $result = $service->getLastestNumber();
        $this->assertEquals($expected, $result);
    }

    public function processDataProvider(): Generator
    {
        yield 'success' => [[
            'status' => true,
            "data" => [
                "month" => "01",
                "num" => 1,
                "year" => "2021",
                "img" => "https://xyz.com/hello.png",
                "title" => "test title",
                "day" => "26"]
        ], [[
            "title" => "test title",
            "image" => "https://xyz.com/hello.png",
            "webUrl" => "https://xyz.com/0",
            "date" => "26-01-2021",
        ]]];

        yield 'fail' => [[
            'status' => false,
            "data" => []
        ], []];
    }

    /**
     * @dataProvider processDataProvider
     */

    public function testProcess(array $data, array $expected): void
    {
        $this->httpService
            ->method('getData')
            ->willReturn($data);
        $this->parameterBagInterface
            ->expects($this->once())
            ->method('get')
            ->willReturn($this->webcomic);

        $service = new WebcomicService(
            $this->httpService,
            $this->parameterBagInterface
        );

        $result = $service->process(1);

        $this->assertEquals($expected , $result);
    }

}
