<?php


namespace App\Tests\Feature\Api\Controller;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ComicControllerTest  extends WebTestCase
{

    public function testComicApiSuccess(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/comic');
        $this->assertResponseIsSuccessful();
        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
    }

    public function testComicApiContainsAllRecords(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/comic');
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertResponseIsSuccessful();
        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('https', $client->getResponse()->getContent());
        $this->assertEquals(20, count($response['data']));

    }
}


