<?php

namespace mySociety\EveryPoliticianPopolo;

class PersonsTest extends \PHPUnit_Framework_TestCase
{
    use ExampleFileTrait;

    const EXAMPLE_TWO_PEOPLE = <<<'NOW'
{
    "persons": [
        {
             "id": "1",
             "name": "Norma Jennings",
             "national_identity": "American"
        },
        {
             "id": "2",
             "name": "Harry Truman",
             "national_identity": "American"
        }
    ]
}
NOW;

    public function testEmptyFileGivesNoPeople()
    {
        $filename = $this->exampleFile('{}');
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $this->assertCount(0, $popolo->persons);
    }

    public function testSinglePersonName()
    {
        $filename = $this->exampleFile('{"persons": [{"name": "Harry Truman"}]}');
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $this->assertCount(1, $popolo->persons);
        $person = $popolo->persons[0];
        $this->assertEquals('Harry Truman', $person->name);
        $this->assertEquals('Harry Truman', $person->nameAt(new \DateTime('2016-01-11')));
    }

    public function testGetFirstPerson()
    {
        $filename = $this->exampleFile(self::EXAMPLE_TWO_PEOPLE);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $this->assertCount(2, $popolo->persons);
        $person = $popolo->persons->first;
        $this->assertEquals('Norma Jennings', $person->name);
        $this->assertEquals('1', $person->id);
    }

    public function testPersonEqualityAndInequality()
    {
        $filename = $this->exampleFile(self::EXAMPLE_TWO_PEOPLE);
        $personNormaA = Popolo::fromFilename($filename)->persons[0];
        $personNormaB = Popolo::fromFilename($filename)->persons[0];
        $personHarry = Popolo::fromFilename($filename)->persons[1];
        unlink($filename);

        $this->assertTrue($personNormaA->equals($personNormaB));
        $this->assertTrue($personHarry->equals($personHarry));
        $this->assertFalse($personNormaA->equals($personHarry));
    }

    public function testFirstFromEmptyFileReturnsNone()
    {
        $filename = $this->exampleFile('{}');
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $this->assertEquals($popolo->persons->first, null);
    }

    public function testFilterOfPeopleNoneMatching()
    {
        $filename = $this->exampleFile(self::EXAMPLE_TWO_PEOPLE);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $matches = $popolo->persons->filter(['name' => 'Dennis the Menace']);
        $this->assertCount(0, $matches);
    }

    public function testFilterOfPeopleOneMatching()
    {
        $filename = $this->exampleFile(self::EXAMPLE_TWO_PEOPLE);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $matches = $popolo->persons->filter(['name' => 'Harry Truman']);
        $this->assertCount(1, $matches);
        $this->assertEquals('Harry Truman', $matches[0]->name);
    }

    /**
     * @expectedException mySociety\EveryPoliticianPopolo\Exceptions\ObjectDoesNotExistException
     */
    public function testGetOfPeopleNoneMatching()
    {
        $filename = $this->exampleFile('{}');
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $popolo->persons->get(['name' => 'Harry Truman']);
    }

    /**
     * @expectedException mySociety\EveryPoliticianPopolo\Exceptions\MultipleObjectsReturnedException
     */
    public function testGetOfPeopleMultipleMatches()
    {
        $filename = $this->exampleFile(self::EXAMPLE_TWO_PEOPLE);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $popolo->persons->get(['nationalIdentity' => 'American']);
    }

    public function testGetOfPeopleOneMatching()
    {
        $filename = $this->exampleFile(self::EXAMPLE_TWO_PEOPLE);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $person = $popolo->persons->get(['name' => 'Harry Truman']);
        $this->assertEquals('2', $person->id);
    }

    public function testGetPersonWithImageAndWikidata()
    {
        // n.b. this is actually the Wikidata ID for the actor who
        // played Harry Truman
        $exampleData = <<<'NOW'
{
    "persons": [
        {
            "name": "Harry Truman",
            "image": "http://twin-peaks.example.org/harry.jpg",
            "identifiers": [
                {
                    "scheme": "wikidata",
                    "identifier": "Q1343162"
                }
            ]
        }
    ]
}
NOW;
        $filename = $this->exampleFile($exampleData);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $person = $popolo->persons->first;
        $this->assertEquals('Q1343162', $person->wikidata);
        $this->assertEquals('http://twin-peaks.example.org/harry.jpg', $person->image);
        $identifiers = [
            [
                'scheme' => 'wikidata',
                'identifier' => 'Q1343162',
            ]
        ];
        $this->assertEquals($identifiers, $person->identifiers);
    }

    public function testPersonToString()
    {
        $filename = $this->exampleFile('{"persons": [{"name": "Paul l\'Astnam\u00e9"}]}');
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $this->assertCount(1, $popolo->persons);
        $person = $popolo->persons[0];
        $this->assertEquals("<Person: Paul l'Astnamé>", (string) $person);
    }

    public function testPersonLinkTwitter()
    {
        $exampleData = <<<'NOW'
{
    "persons": [
        {
            "name": "Harry Truman",
            "links": [
                {
                    "note": "twitter",
                    "url": "https://twitter.com/notarealtwitteraccountforharry"
                }
            ]
        }
    ]
}
NOW;
        $filename = $this->exampleFile($exampleData);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $person = $popolo->persons->first;
        $this->assertEquals('notarealtwitteraccountforharry', $person->twitter);
        $this->assertEquals(['notarealtwitteraccountforharry'], $person->twitterAll);
    }

    public function testPersonContactDetailTwitterAndContactDetailsList()
    {
        $exampleData = <<<'NOW'
{
    "persons": [
        {
            "name": "Harry Truman",
            "contact_details": [
                {
                    "type": "twitter",
                    "value": "notarealtwitteraccountforharry"
                },
                {
                    "type": "phone",
                    "value": "555-5555"
                }
            ]
        }
    ]
}
NOW;
        $filename = $this->exampleFile($exampleData);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $person = $popolo->persons->first;
        $this->assertEquals('notarealtwitteraccountforharry', $person->twitter);
        $contactDetails = [
            [
                "type" => "twitter",
                "value" => "notarealtwitteraccountforharry",
            ],
            [
                "type" => "phone",
                "value" => "555-5555",
            ],
        ];
        $this->assertEquals($contactDetails, $person->contactDetails);
        $this->assertEquals(['notarealtwitteraccountforharry'], $person->twitterAll);
    }

    public function testTwitterPropertyNoneForNoTwitter()
    {
        $filename = $this->exampleFile(self::EXAMPLE_TWO_PEOPLE);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $person = $popolo->persons->first;
        $this->assertNull($person->twitter);
    }

    public function testSortName()
    {
        $exampleData = <<<'NOW'
{
    "persons": [
        {
            "name": "Harry Truman",
            "sort_name": "Truman"
        }
    ]
}
NOW;
        $filename = $this->exampleFile($exampleData);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $person = $popolo->persons->first;
        $this->assertEquals('Truman', $person->sortName);
    }

    public function testSimplePersonFields()
    {
        $exampleData = <<<'NOW'
{
    "persons": [
        {
            "name": "Harry Truman",
            "email": "harry@example.org",
            "image": "http://twin-peaks.example.org/harry.jpg",
            "gender": "male",
            "honorific_prefix": "Sheriff",
            "honorific_suffix": "Bookhouse Boy",
            "biography": "Harry S. Truman is the sheriff of Twin Peaks",
            "summary": "He assists Dale Cooper in the Laura Palmer case",
            "given_name": "Harry",
            "family_name": "Truman"
        }
    ]
}
NOW;
        $filename = $this->exampleFile($exampleData);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $person = $popolo->persons->first;

        $this->assertEquals('Harry Truman', $person->name);
        $this->assertEquals('harry@example.org', $person->email);
        $this->assertEquals('http://twin-peaks.example.org/harry.jpg', $person->image);
        $this->assertEquals('male', $person->gender);
        $this->assertEquals('Sheriff', $person->honorificPrefix);
        $this->assertEquals('Bookhouse Boy', $person->honorificSuffix);
        $this->assertEquals('Harry S. Truman is the sheriff of Twin Peaks', $person->biography);
        $this->assertEquals('He assists Dale Cooper in the Laura Palmer case', $person->summary);
        $this->assertEquals('Harry', $person->givenName);
        $this->assertEquals('Truman', $person->familyName);
    }

    public function testMissingBirthAndDeathDates()
    {
        $exampleData = <<<'NOW'
{
    "persons": [
        {
            "name": "Harry Truman"
        }
    ]
}
NOW;
        $filename = $this->exampleFile($exampleData);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $person = $popolo->persons->first;
        $this->assertNull($person->birthDate);
        $this->assertNull($person->deathDate);
    }

    public function testFullBirthAndDeathDates()
    {
        $exampleData = <<<'NOW'
{
    "persons": [
        {
            "name": "Harry Truman",
            "birth_date": "1946-01-24",
            "death_date": "2099-12-31"
        }
    ]
}
NOW;
        $filename = $this->exampleFile($exampleData);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $person = $popolo->persons->first;
        $this->assertEquals(new \DateTime('1946-01-24'), $person->birthDate);
        $this->assertEquals(new \DateTime('2099-12-31'), $person->deathDate);
    }

    public function testPhoneAndFax()
    {
        $exampleData = <<<'NOW'
{
    "persons": [
        {
            "name": "Harry Truman",
            "contact_details": [
                {"type": "phone", "value": "9304832"},
                {"type": "fax", "value": "9304833"}
            ]
        }
    ]
}
NOW;
        $filename = $this->exampleFile($exampleData);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $person = $popolo->persons->first;
        $this->assertEquals('9304832', $person->phone);
        $this->assertEquals(['9304832'], $person->phoneAll);
        $this->assertEquals('9304833', $person->fax);
        $this->assertEquals(['9304833'], $person->faxAll);
    }

    public function testPersonFacebookAndLinksList()
    {
        $exampleData = <<<'NOW'
{
    "persons": [
        {
            "name": "Harry Truman",
            "links": [
                {
                    "note": "facebook",
                    "url": "https://facebook.example.com/harry-s-truman"
                },
                {
                    "note": "wikia",
                    "url": "http://twinpeaks.wikia.com/wiki/Harry_S._Truman"
                }
            ]
        }
    ]
}
NOW;
        $filename = $this->exampleFile($exampleData);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $person = $popolo->persons->first;
        $this->assertEquals('https://facebook.example.com/harry-s-truman', $person->facebook);
        $this->assertEquals(['https://facebook.example.com/harry-s-truman'], $person->facebookAll);
        $links = [
            [
                "note" => "facebook",
                "url" => "https://facebook.example.com/harry-s-truman",
            ],
            [
                "note" => "wikia",
                "url" => "http://twinpeaks.wikia.com/wiki/Harry_S._Truman",
            ],
        ];
        $this->assertEquals($links, $person->links);
    }

    public function testPersonImages()
    {
        $urlA = 'http://www.parlamentra.org/upload/iblock/b85/%D1%80%D0%B0%D0%BC.jpg';
        $urlB = 'https://upload.wikimedia.org/';
        $urlB .= 'wikipedia/commons/a/a3/Бганба_Валерий_Рамшухович.jpg';
        $exampleData = <<<HERE
{
    "persons": [
        {
            "name": "Бганба Валерий Рамшухович",
            "images": [
                {
                    "url": "$urlA"
                },
                {
                    "url": "$urlB"
                }
            ]
        }
    ]
}
HERE;
        $filename = $this->exampleFile($exampleData);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $person = $popolo->persons->first;
        $images = [
            ['url' => $urlA],
            ['url' => $urlB],
        ];
        $this->assertEquals($images, $person->images);
    }

    public function testPersonOtherNames()
    {
        $exampleData = <<<'NOW'
{
    "persons": [
        {
            "id": "john-q-public",
            "name": "Mr. John Q. Public, Esq.",
            "other_names": [
                {
                    "name": "Mr. Ziggy Q. Public, Esq.",
                    "start_date": "1920-01",
                    "end_date": "1949-12-31",
                    "note": "Birth name"
                },
                {
                    "name": "Dragonsbane",
                    "note": "LARP character name"
                }
            ]
        }
    ]
}
NOW;
        $filename = $this->exampleFile($exampleData);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $person = $popolo->persons->first;
        $otherNames = [
            [
                'end_date' => '1949-12-31',
                'name' => 'Mr. Ziggy Q. Public, Esq.',
                'note' => 'Birth name',
                'start_date' => '1920-01',
            ],
            [
                "name" => "Dragonsbane",
                "note" => "LARP character name",
            ],
        ];
        $this->assertEquals($otherNames, $person->otherNames);
    }


    public function testPersonNoOtherNames()
    {
        $exampleData = <<<'NOW'
{
    "persons": [
        {
            "id": "john-q-public",
            "name": "Mr. John Q. Public, Esq."
        }
    ]
}
NOW;
        $filename = $this->exampleFile($exampleData);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $person = $popolo->persons->first;
        $this->assertEquals([], $person->otherNames);
    }


    public function testPersonSources()
    {
        $exampleData = <<<'NOW'
{
    "persons": [
        {
            "id": "john-q-public",
            "name": "Mr. John Q. Public, Esq.",
            "sources": [
                {
                    "note": "His homepage",
                    "url": "http://example.org/john-q-public"
                }
            ]
        }
    ]
}
NOW;
        $filename = $this->exampleFile($exampleData);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $person = $popolo->persons->first;
        $sources = [
            [
                'note' => 'His homepage',
                'url' => 'http://example.org/john-q-public',
            ],
        ];
        $this->assertEquals($sources, $person->sources);
    }

    public function testPersonNameAtNoHistoric()
    {
        $exampleData = <<<'NOW'
{
    "persons": [
        {
            "name": "Bob",
            "other_names": [
                {
                    "name": "Robert",
                    "start_date": "2000-01-01"
                }
            ]
        }
    ]
}
NOW;
        $filename = $this->exampleFile($exampleData);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $person = $popolo->persons->first;
        $this->assertEquals('Bob', $person->nameAt(new \DateTime('2016-01-11')));
    }

    public function testPersonNameAtHistoric()
    {
        $exampleData = <<<'NOW'
{
    "persons": [
        {
            "name": "Bob",
            "other_names": [
                {
                    "name": "Robert",
                    "start_date": "1989-01-01",
                    "end_date": "1999-12-31"
                }
            ]
        }
    ]
}
NOW;
        $filename = $this->exampleFile($exampleData);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $person = $popolo->persons->first;
        $this->assertEquals('Robert', $person->nameAt(new \DateTime('1990-06-01')));
    }

    public function testPersonNameAtHistoricNoneOverlap()
    {
        $exampleData = <<<'NOW'
{
    "persons": [
        {
            "name": "Bob",
            "other_names": [
                {
                    "name": "Robert",
                    "start_date": "1989-01-01",
                    "end_date": "1999-12-31"
                }
            ]
        }
    ]
}
NOW;
        $filename = $this->exampleFile($exampleData);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $person = $popolo->persons->first;
        $this->assertEquals('Bob', $person->nameAt(new \DateTime('2000-01-01')));
    }

    /**
     * @expectedException        Exception
     * @expectedExceptionMessage Multiple names for <Person: Bob> found at date 1996-01-01
     */
    public function testPersonMultipleNamesAtOneDate()
    {
        $exampleData = <<<'NOW'
{
    "persons": [
        {
            "name": "Bob",
            "other_names": [
                {
                    "name": "Robert",
                    "start_date": "1989-01-01",
                    "end_date": "1999-12-31"
                },
                {
                    "name": "Bobby",
                    "start_date": "1989-01-01",
                    "end_date": "2012-12-31"
                }
            ]
        }
    ]
}
NOW;
        $filename = $this->exampleFile($exampleData);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $person = $popolo->persons->first;
        $person->nameAt(new \DateTime('1996-01-01'));
    }

    public function testHashMagicMethod()
    {
        // Not sure how to implement this test in PHP!
    }

    public function testEqualityAndInequalityNotImplemented()
    {
        $filename = $this->exampleFile(self::EXAMPLE_TWO_PEOPLE);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $person = $popolo->persons->first;
        $this->assertFalse($person == "a string, not a person");
        $this->assertNotEquals($person, "a string not a person");
    }
}
