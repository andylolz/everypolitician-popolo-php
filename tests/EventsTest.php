<?php

namespace EveryPolitician\EveryPoliticianPopolo;

use \DateTime;

class EventsTest extends \PHPUnit_Framework_TestCase
{
    use ExampleFileTrait;

    const EXAMPLE_EVENT_JSON = <<<'NOW'
{
    "events": [
        {
            "classification": "legislative period",
            "end_date": "2015-03-23",
            "id": "term/12",
            "identifiers": [
                {
                    "identifier": "Q967549",
                    "scheme": "wikidata"
                }
            ],
            "name": "12th Riigikogu",
            "organization_id": "1ba661a9-22ad-4d0f-8a60-fe8e28f2488c",
            "start_date": "2011-03-27"
        }
    ],
    "organizations": [
        {
            "classification": "legislature",
            "id": "1ba661a9-22ad-4d0f-8a60-fe8e28f2488c",
            "identifiers": [
                {
                    "identifier": "Q217799",
                    "scheme": "wikidata"
                }
            ],
            "name": "Riigikogu",
            "seats": 101
         }
    ]
}
NOW;

    const EXAMPLE_EVENT_NON_ASCII_JSON = <<<'NOW'
{
    "events": [
        {
            "classification": "legislative period",
            "end_date": "2015-03-23",
            "id": "2015",
            "name": "2015—",
            "start_date": "2015-03-01"
        }
    ]
}
NOW;

    public function testEmptyFileGivesNoEvents()
    {
        $popolo = new Popolo([]);
        $this->assertCount(0, $popolo->events);
    }

    public function testSingleEventWithLabel()
    {
        $filename = $this->exampleFile(self::EXAMPLE_EVENT_JSON);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $this->assertCount(1, $popolo->events);
        $event = $popolo->events[0];
        $this->assertEquals('12th Riigikogu', $event->name);
    }

    public function testStartAndEndDates()
    {
        $filename = $this->exampleFile(self::EXAMPLE_EVENT_JSON);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $event = $popolo->events->first;
        $this->assertEquals(new DateTime('2011-03-27'), $event->startDate);
        $this->assertEquals(new DateTime('2015-03-23'), $event->endDate);
    }

    public function testEventId()
    {
        $filename = $this->exampleFile(self::EXAMPLE_EVENT_JSON);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $event = $popolo->events->first;
        $this->assertEquals('term/12', $event->id);
    }

    public function testEventOrganizationId()
    {
        $filename = $this->exampleFile(self::EXAMPLE_EVENT_JSON);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $event = $popolo->events->first;
        $this->assertEquals('1ba661a9-22ad-4d0f-8a60-fe8e28f2488c', $event->organizationId);
    }

    public function testEventOrganization()
    {
        $filename = $this->exampleFile(self::EXAMPLE_EVENT_JSON);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $event = $popolo->events->first;
        $org = $popolo->organizations->first;
        $this->assertTrue($event->organization->equals($org));
    }

    public function testEventClassification()
    {
        $filename = $this->exampleFile(self::EXAMPLE_EVENT_JSON);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $event = $popolo->events->first;
        $this->assertEquals('legislative period', $event->classification);
    }

    public function testEventIdentifiers()
    {
        $filename = $this->exampleFile(self::EXAMPLE_EVENT_JSON);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $event = $popolo->events->first;
        $identifiers = [
            [
                "identifier" => "Q967549",
                "scheme" => "wikidata",
            ]
        ];
        $this->assertEquals($identifiers, $event->identifiers);
    }

    public function testEventToString()
    {
        $filename = $this->exampleFile(self::EXAMPLE_EVENT_JSON);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $event = $popolo->events->first;
        $this->assertEquals('<Event: 12th Riigikogu>', (string) $event);
    }

    public function testEventToStringNonAscii()
    {
        $filename = $this->exampleFile(self::EXAMPLE_EVENT_NON_ASCII_JSON);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $event = $popolo->events->first;
        $this->assertEquals('<Event: 2015—>', (string) $event);
    }

    public function testEventIdentityEqualityAndInequality()
    {
        $filename = $this->exampleFile(self::EXAMPLE_EVENT_JSON);
        $popoloA = Popolo::fromFilename($filename);
        unlink($filename);
        $filename = $this->exampleFile(self::EXAMPLE_EVENT_JSON);
        $popoloB = Popolo::fromFilename($filename);
        unlink($filename);

        $eventA = $popoloA->events->first;
        $eventB = $popoloB->events->first;
        $this->assertTrue($eventA->equals($eventB));
    }

    public function testTermCurrentAtTrue()
    {
        $filename = $this->exampleFile(self::EXAMPLE_EVENT_JSON);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $event = $popolo->events[0];
        $this->assertTrue($event->currentAt(new DateTime('2013-01-01')));
    }

    public function testTermCurrentAtFalseBefore()
    {
        $filename = $this->exampleFile(self::EXAMPLE_EVENT_JSON);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $event = $popolo->events[0];
        $this->assertFalse($event->currentAt(new DateTime('1980-01-01')));
    }

    public function testTermCurrentAtFalseAfter()
    {
        $filename = $this->exampleFile(self::EXAMPLE_EVENT_JSON);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $event = $popolo->events[0];
        $this->assertFalse($event->currentAt(new DateTime('2020-01-01')));
    }

    // TODO: Need a way to mock current date
    // public function testTermCurrentTrue()
    // {
    //     $filename = $this->exampleFile(self::EXAMPLE_EVENT_JSON);
    //     $popolo = Popolo::fromFilename($filename);
    //     unlink($filename);
    //
    //     $event = $popolo->events[0];
    //     $this->assertTrue($event->current);
    // }
}
