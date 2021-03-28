<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;


use App\Service\HttpService;
use Generator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class HttpServiceTest extends TestCase
{

    private $httpClientInterface;

    public function setUp() :void
    {
        $this->httpClientInterface = $this->createMock(HttpClientInterface::class);
    }

    public function invokeMethod(&$object, string $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    public function getResponseDataProvider(): Generator
    {
        yield 'successful request' => [ 200, 'GET', 'xyz.com'];

        yield 'fail request' => [401, 'GET', 'xyz.com'];
    }

    /**
     * @dataProvider getResponseDataProvider
     */

    public function testGetResponse(int $httpCode, string $method, string $url): void
    {
        $expectedResponse = new MockResponse(
            '', [
                'http_code' => $httpCode
            ]
        );
        $this->httpClientInterface
            ->method('request')
            ->willReturn($expectedResponse);


        $service = new HttpService(
            $this->httpClientInterface
        );

        $result = $this->invokeMethod($service, 'getResponse', [$method, $url]);

        $this->assertEquals( $expectedResponse, $result);
    }
}

