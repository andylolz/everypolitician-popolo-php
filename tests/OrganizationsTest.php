<?php

namespace EveryPolitician\EveryPoliticianPopolo;

use \DateTime;

class OrganizationsTest extends \PHPUnit_Framework_TestCase
{
    use ExampleFileTrait;

    public function testEmptyFileGivesNoOrganizations()
    {
        $filename = $this->exampleFile('{}');
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $this->assertCount(0, $popolo->organizations);
    }

    public function testSingleOrganizationName()
    {
        $json = <<<'NOW'
{
    "organizations": [{"name": "Starfleet"}]
}
NOW;
        $filename = $this->exampleFile($json);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $this->assertCount(1, $popolo->organizations);
        $o = $popolo->organizations[0];
        $this->assertEquals('Starfleet', $o->name);
    }

    public function testWikidataPropertyAndId()
    {
        $json = <<<'NOW'
{
    "organizations": [
        {
            "id": "starfleet",
            "name": "Starfleet",
            "identifiers": [
                {
                    "identifier": "Q288523",
                    "scheme": "wikidata"
                }
            ]
        }
    ]
}
NOW;
        $filename = $this->exampleFile($json);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $this->assertCount(1, $popolo->organizations);
        $o = $popolo->organizations[0];
        $this->assertEquals('Q288523', $o->wikidata);
        $this->assertEquals('starfleet', $o->id);
    }

    public function testIdentifiersList()
    {
        $json = <<<'NOW'
{
    "organizations": [
        {
            "id": "starfleet",
            "name": "Starfleet",
            "identifiers": [
                {
                    "identifier": "Q288523",
                    "scheme": "wikidata"
                },
                {
                    "identifier": "123456",
                    "scheme": "made-up-id"
                }
            ]
        }
    ]
}
NOW;
        $filename = $this->exampleFile($json);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $this->assertCount(1, $popolo->organizations);
        $o = $popolo->organizations[0];
        $identifiers = [
            [
                'identifier' => 'Q288523',
                'scheme' => 'wikidata',
            ],
            [
                'identifier' => '123456',
                'scheme' => 'made-up-id',
            ],
        ];
        $this->assertEquals($identifiers, $o->identifiers);
    }

    public function testClassificationProperty()
    {
        $json = <<<'NOW'
{
    "organizations": [
        {
            "id": "starfleet",
            "name": "Starfleet",
            "classification": "military"
        }
    ]
}
NOW;
        $filename = $this->exampleFile($json);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $this->assertCount(1, $popolo->organizations);
        $o = $popolo->organizations[0];
        $this->assertEquals('military', $o->classification);
    }

    public function testNoMatchingIdentifier()
    {
        $json = <<<'NOW'
{
    "organizations": [
        {
            "id": "starfleet",
            "name": "Starfleet"        }
    ]
}
NOW;
        $filename = $this->exampleFile($json);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $this->assertCount(1, $popolo->organizations);
        $o = $popolo->organizations->first;
        $this->assertEquals(null, $o->wikidata);
    }

    public function testOrganizationToString()
    {
        $json = '{"organizations": [{"name": "M\u00e9decins Sans Fronti\u00e8res"}]}';
        $filename = $this->exampleFile($json);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $this->assertCount(1, $popolo->organizations);
        $o = $popolo->organizations[0];
        $this->assertEquals('<Organization: Médecins Sans Frontières>', (string) $o);
    }

    public function testOrganizationImage()
    {
        $popolo = new Popolo([
            'organizations' => [
                [
                    'name' => 'ACME corporation',
                    'image' => 'http://example.org/acme.jpg',
                ]
            ]
        ]);

        $this->assertCount(1, $popolo->organizations);
        $o = $popolo->organizations->first;
        $this->assertEquals('http://example.org/acme.jpg', $o->image);
    }

    public function testOrganizationSeats()
    {
        $popolo = new Popolo([
            'organizations' => [
                [
                    'name' => 'House of Commons',
                    'seats' => 650,
                ]
            ]
        ]);
        $o = $popolo->organizations->first;
        $this->assertEquals(650, $o->seats);
    }

    public function testOrganizationFoundingAndDissolutionDates()
    {
        $popolo = new Popolo([
            'organizations' => [
                [
                    'name' => 'ACME corporation',
                    'founding_date' => '1950-01-20',
                    'dissolution_date' => '2000-11-15',
                ]
            ]
        ]);
        $o = $popolo->organizations->first;
        $this->assertEquals(new DateTime('1950-01-20'), $o->foundingDate);
        $this->assertEquals(new DateTime('2000-11-15'), $o->dissolutionDate);
    }

    public function testOrganizationOtherNames()
    {
        $json = <<<'NOW'
{
    "organizations": [
        {
             "id": "abc-inc",
             "name": "ABC, Inc.",
             "other_names": [
                 {
                     "name": "Bob's Diner",
                     "start_date": "1950-01-01",
                     "end_date": "1954-12-31"
                 },
                 {
                     "name": "Joe's Diner",
                     "start_date": "1955-01-01"
                 },
                 {
                     "name": "Famous Joe's"
                 }
             ]
        }
    ]
}
NOW;
        $filename = $this->exampleFile($json);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $this->assertCount(1, $popolo->organizations);
        $o = $popolo->organizations[0];
        $otherNames = [
           [
               'name' => "Bob's Diner",
               'start_date' => '1950-01-01',
               'end_date' => '1954-12-31',
            ],
            [
                'name' => "Joe's Diner",
                'start_date' => '1955-01-01',
            ],
            [
                'name' => "Famous Joe's",
            ],
        ];
        $this->assertEquals($otherNames, $o->otherNames);
    }

    public function testOrganizationLinksList()
    {
        $json = <<<'NOW'
{
    "organizations": [
        {
            "id": "starfleet",
            "name": "Starfleet",
            "links": [
                {
                    "url": "https://en.wikipedia.org/wiki/Starfleet",
                    "note": "Wikipedia"
                },
                {
                    "url": "http://memory-alpha.wikia.com/wiki/Starfleet",
                    "note": "Memory Alpha"
                }
            ]
        }
    ]
}
NOW;
        $filename = $this->exampleFile($json);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $this->assertCount(1, $popolo->organizations);
        $o = $popolo->organizations[0];
        $links = [
            [
                'url' => 'https://en.wikipedia.org/wiki/Starfleet',
                'note' => 'Wikipedia',
            ],
            [
                'url' => 'http://memory-alpha.wikia.com/wiki/Starfleet',
                'note' => 'Memory Alpha',
            ],
        ];
        $this->assertEquals($links, $o->links);
    }

    public function testOrganisationEquality()
    {
        $json = <<<'NOW'
{
    "organizations": [
        {
            "id": "starfleet",
            "name": "Starfleet",
            "identifiers": [
                {
                    "identifier": "Q288523",
                    "scheme": "wikidata"
                }
            ]
        }
    ]
}
NOW;
        $filename = $this->exampleFile($json);
        $popolo = Popolo::fromFilename($filename);
        $oA = $popolo->organizations[0];
        unlink($filename);
        $filename = $this->exampleFile($json);
        $popolo = Popolo::fromFilename($filename);
        $oB = $popolo->organizations[0];
        unlink($filename);

        $this->assertTrue($oA->equals($oB));
    }
}
