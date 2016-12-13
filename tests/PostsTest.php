<?php

namespace EveryPolitician\EveryPoliticianPopolo;

use \PHPUnit_Framework_TestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PostsTest extends PHPUnit_Framework_TestCase
{
    use ExampleFileTrait;

    const EXAMPLE_POST_JSON = <<<'NOW'
{
    "posts": [
        {
            "id": "nominated_representative",
            "label": "Nominated Representative",
            "organization_id": "574eff8e-8171-4f2b-8279-60ed8dec1a2a"
        },
        {
            "id": "women's_representative",
            "label": "Women's Representative",
            "organization_id": "574eff8e-8171-4f2b-8279-60ed8dec1a2a"
        }
    ],
    "organizations": [
        {
            "classification": "legislature",
            "id": "574eff8e-8171-4f2b-8279-60ed8dec1a2a",
            "identifiers": [
                {
                    "identifier": "Q1701225",
                    "scheme": "wikidata"
                }
            ],
            "name": "National Assembly",
            "seats": 349
        }
    ]
}
NOW;

    public function testEmptyFileGivesNoPosts()
    {
        $popolo = new Popolo([]);
        $this->assertCount(0, $popolo->posts);
    }

    public function testSinglePostWithLabel()
    {
        $filename = $this->exampleFile(self::EXAMPLE_POST_JSON);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $this->assertCount(2, $popolo->posts);
        $post = $popolo->posts[0];
        $this->assertEquals('Nominated Representative', $post->label);
    }

    public function testPostId()
    {
        $filename = $this->exampleFile(self::EXAMPLE_POST_JSON);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $post = $popolo->posts->first;
        $this->assertEquals('nominated_representative', $post->id);
    }



    public function testPostHasOrganizationId()
    {
        $filename = $this->exampleFile(self::EXAMPLE_POST_JSON);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $this->assertCount(2, $popolo->posts);
        $post = $popolo->posts[0];
        $this->assertEquals('574eff8e-8171-4f2b-8279-60ed8dec1a2a', $post->organizationId);
    }

    public function testPostHasOrganization()
    {
        $filename = $this->exampleFile(self::EXAMPLE_POST_JSON);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $nationalAssembly = $popolo->organizations->first;
        $nomRep = $popolo->posts->first;
        $this->assertTrue($nomRep->organization->equals($nationalAssembly));
    }

    public function testAreaToString()
    {
        $filename = $this->exampleFile(self::EXAMPLE_POST_JSON);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $post = $popolo->posts->first;
        $this->assertEquals('<Post: Nominated Representative>', (string) $post);
    }

    public function testPostIdentityEqualityAndInequality()
    {
        $filename = $this->exampleFile(self::EXAMPLE_POST_JSON);
        $popoloA = Popolo::fromFilename($filename);
        unlink($filename);
        $filename = $this->exampleFile(self::EXAMPLE_POST_JSON);
        $popoloB = Popolo::fromFilename($filename);
        unlink($filename);

        $postA = $popoloA->posts->first;
        $postB = $popoloB->posts->first;
        $this->assertTrue($postA->equals($postB));
    }
}
