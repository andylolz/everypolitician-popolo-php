<?php

namespace mySociety\EveryPoliticianPopolo;

class LoadTest extends \PHPUnit_Framework_TestCase
{
    use ExampleFileTrait;

    public function tearDown()
    {
        \Mockery::close();
    }

    public function testCanCreateFromAFilename()
    {
        $filename = $this->exampleFile('{}');
        Popolo::fromFilename($filename);
    }

    /**
     * @expectedException        PHPUnit_Framework_Error_Warning
     * @expectedExceptionMessage file_get_contents(non-existent-file.json): failed to open stream
     */
    public function testFailsToCreateFromANonexistentFilename()
    {
        Popolo::fromFilename('non-existent-file.json');
    }

    public function testCreateFromUrl()
    {
        $body = json_encode([
            'persons' => [['name' => 'Joe Bloggs']]
        ]);
        $response = new \GuzzleHttp\Psr7\Response(200, ['Content-Type' => 'application/json'], $body);
        $client = \Mockery::mock('\GuzzleHttp\Client');
        $client->shouldReceive('get')->once()->andReturn($response);

        $popolo = Popolo::fromUrl('http://example.org/popolo.json', $client);
        $this->assertEquals('Joe Bloggs', $popolo->persons->first->name);
    }
}
