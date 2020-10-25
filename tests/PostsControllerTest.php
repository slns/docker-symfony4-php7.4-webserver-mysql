<?php

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

/**
 * PostControllerTest
 * @group group
 */
class PostControllerTest extends TestCase
{

    private $http;

    public function setUp(): void
    {
        $baseUri = 'http://localhost:8000';
        $this->http = new Client(['base_uri' => $baseUri]);

    }

    public function tearDown(): void
    {
        $this->http = null;
    }

    /** @test */
    public function testCreatePostController()
    {
        $response = $this->http->request('POST', '/api/posts/', [
            'form_params' => [
                'title' => 'title abc',
                'description' => 'description 123',
                'content' => '11111111111111123',
                'status' => '1',
            ]
        ]);

        $this->assertEquals(201, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
    }

    /** @test */
    public function testIndexPostController()
    {
        $response = $this->http->request('GET', '/api/posts');

        $this->assertEquals(200, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
    }

    /** @test */
    public function testShowPostController()
    {
        $data = [
            "id" => 1,
            "title" => "nam",
            "description" => "Ducimus quia dolores odit non numquam quas occaecati modi.",
            "content" => "Consequatur ducimus at qui. Omnis hic eius eligendi est vel et praesentium quia. Doloremque deserunt ea ut optio. Aliquid et est assumenda optio eos et.",
            "status" => false,
            "createdAt" => "1980-04-08T21:16:27+00:00",
            "updatedAt" => "1970-02-20T13:17:05+00:00",
            "channel" => [
                "id" => 7,
                "name" => "bicycle",
                "__initializer__" => null,
                "__cloner__" => null,
                "__isInitialized__" => true,
            ]];

        $response = $this->http->request('GET', '/api/posts/1');

        $this->assertEquals(200, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);

        $dataJSON = json_decode($response->getBody())->{"data"};
        $this->assertIsObject($dataJSON);
        $this->assertEquals($this->toObject($data), $dataJSON);

    } 

    /** @test */
    public function testShowUpdateController()
    {
        $response = $this->http->request('PUT', '/api/posts/2', [
            'form_params' => [
                'title' => 'title abc',
                'description' => 'description 123',
                'content' => '11111111111111123',
                'status' => '1',
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);

        $dataJSON = json_decode($response->getBody())->{"data"};
        $this->assertIsObject($dataJSON);

    }

    private function toObject($array)
    {
        $obj = new stdClass();
        foreach ($array as $key => $val) {
            $obj->$key = is_array($val) ? $this->toObject($val) : $val;
        }
        return $obj;
    }
}
