<?php

namespace EveryPolitician\EveryPoliticianPopolo;

class AreasTest extends \PHPUnit_Framework_TestCase
{
    use ExampleFileTrait;

    const EXAMPLE_AREA = [
        "id" => "area/tartuLinn",
        "identifiers" => [
            [
                "identifier" => "Q3032626",
                "scheme" => "wikidata",
            ],
        ],
        "name" => "Tartu linn",
        "other_names" => [
            [
                "lang" => "fr",
                "name" => "Dixième circonscription législative d'Estonie",
                "note" => "multilingual",
            ],
            [
                "lang" => "et",
                "name" => "Valimisringkond nr 10",
                "note" => "multilingual",
            ],
            [
                "lang" => "en",
                "name" => "Electoral District 10 (Tartu)",
                "note" => "multilingual",
            ],
        ],
        "type" => "constituency",
    ];

    public function testEmptyFileGivesNoAreas()
    {
        $popolo = new Popolo([]);
        $this->assertCount(0, $popolo->areas);
    }

    public function testSingleAreaWithName()
    {
        $popolo = new Popolo(["areas" => [self::EXAMPLE_AREA]]);
        $this->assertCount(1, $popolo->areas);
        $area = $popolo->areas[0];
        $this->assertEquals('Tartu linn', $area->name);
    }

    public function testAreaId()
    {
        $popolo = new Popolo(["areas" => [self::EXAMPLE_AREA]]);
        $area = $popolo->areas[0];
        $this->assertEquals('area/tartuLinn', $area->id);
    }


    public function testAreaType()
    {
        $popolo = new Popolo(["areas" => [self::EXAMPLE_AREA]]);
        $area = $popolo->areas[0];
        $this->assertEquals('constituency', $area->type);
    }


    public function testAreaIdentifiers()
    {
        $popolo = new Popolo(["areas" => [self::EXAMPLE_AREA]]);
        $area = $popolo->areas[0];
        $identifiers = [
            [
                "identifier" => "Q3032626",
                "scheme" => "wikidata",
            ]
        ];
        $this->assertEquals($identifiers, $area->identifiers);
    }

    public function testAreaOtherNames()
    {
        $popolo = new Popolo(["areas" => [self::EXAMPLE_AREA]]);
        $area = $popolo->areas[0];
        $otherNames = [
            [
                "lang" => "fr",
                "name" => "Dixième circonscription législative d'Estonie",
                "note" => "multilingual",
            ],
            [
                "lang" => "et",
                "name" => "Valimisringkond nr 10",
                "note" => "multilingual",
            ],
            [
                "lang" => "en",
                "name" => "Electoral District 10 (Tartu)",
                "note" => "multilingual",
            ],
        ];
        $this->assertEquals($otherNames, $area->otherNames);
    }

    public function testAreaWikidata()
    {
        $popolo = new Popolo(["areas" => [self::EXAMPLE_AREA]]);
        $area = $popolo->areas[0];
        $this->assertEquals('Q3032626', $area->wikidata);
    }

    public function testAreaToString()
    {
        $popolo = new Popolo(["areas" => [self::EXAMPLE_AREA]]);
        $area = $popolo->areas[0];
        $this->assertEquals('<Area: Tartu linn>', (string) $area);
    }

    public function testAreaIdentityEqualityAndInequality()
    {
        $popoloA = new Popolo(["areas" => [self::EXAMPLE_AREA]]);
        $areaA = $popoloA->areas[0];
        $popoloB = new Popolo(["areas" => [self::EXAMPLE_AREA]]);
        $areaB = $popoloB->areas[0];
        $this->assertTrue($areaA->equals($areaB));
    }
}
