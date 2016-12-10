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

    public function testFailsToCreateFromANonexistentFilename()
    {
        $filename = 'non-existent-file.json';
        $this->expectException(\PHPUnit_Framework_Error_Warning::class);
        $msg = "file_get_contents($filename): failed to open stream: No such file or directory";
        $this->expectExceptionMessage($msg);
        Popolo::fromFilename($filename);
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
