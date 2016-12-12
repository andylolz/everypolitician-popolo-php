<?php

namespace EveryPolitician\EveryPoliticianPopolo;

class MembershipsTest extends \PHPUnit_Framework_TestCase
{
    use ExampleFileTrait;

    const EXAMPLE_SINGLE_MEMBERSHIP = <<<'NOW'
{
    "persons": [
        {
            "id": "SP-937-215",
            "name": "Jean-Luc Picard"
        }
    ],
    "organizations": [
        {
            "id": "starfleet",
            "name": "Starfleet"
        }
    ],
    "memberships": [
        {
            "person_id": "SP-937-215",
            "organization_id": "starfleet",
            "role": "student",
            "start_date": "2327-12-01"
        }
    ]
}
NOW;

    const EXAMPLE_MEMBERSHIP_ALL_FIELDS = <<<'NOW'
{
    "areas": [
        {
            "id": "dunny-on-the-wold",
            "name": "Dunny-on-the-Wold"
        }
    ],
    "events": [
        {
            "classification": "legislative period",
            "id": "pitt",
            "name": "Parliamentary Period",
            "start_date": "1783-12-19",
            "end_date": "1801-01-01"
        }
    ],
    "persons": [
        {
            "id": "1234",
            "name": "Edmund Blackadder"
        }
    ],
    "posts": [
        {
            "id": "dunny-on-the-wold-seat",
            "label": "Member of Parliament for Dunny-on-the-Wold"
        }
    ],
    "organizations": [
        {
            "id": "commons",
            "name": "House of Commons"
        },
        {
            "id": "adder",
            "name": "Adder Party",
            "classification": "party"
        }
    ],
    "memberships": [
        {
            "area_id": "dunny-on-the-wold",
            "end_date": "1784-05-23",
            "legislative_period_id": "pitt",
            "on_behalf_of_id": "adder",
            "organization_id": "commons",
            "person_id": "1234",
            "post_id": "dunny-on-the-wold-seat",
            "role": "candidates",
            "start_date": "1784-03-01"
        }
    ]
}
NOW;

    const EXAMPLE_MULTIPLE_MEMBERSHIPS = <<<'NOW'
{
    "persons": [
        {
            "id": "SP-937-215",
            "name": "Jean-Luc Picard"
        },
        {
            "id": "SC-231-427",
            "name": "William Riker"
        }
    ],
    "organizations": [
        {
            "id": "starfleet",
            "name": "Starfleet"
        },
        {
            "id": "gardening-club",
            "name": "Boothby's Gardening Club"
        }
    ],
    "memberships": [
        {
            "person_id": "SP-937-215",
            "organization_id": "starfleet",
            "start_date": "2327-12-01"
        },
        {
            "person_id": "SP-937-215",
            "organization_id": "gardening-club",
            "start_date": "2323-01-01",
            "end_date": "2327-11-31"
        },
        {
            "person_id": "SC-231-427",
            "organization_id": "starfleet",
            "start_date": "2357-03-08"
        }
    ]
}
NOW;

    public function testEmptyFileGivesNoMemberships()
    {
        $filename = $this->exampleFile('{}');
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $this->assertCount(0, $popolo->memberships);
    }

    /**
     * @expectedException        PHPUnit_Framework_Error
     * @expectedExceptionMessage Undefined property
     */
    public function testMembershipShouldNotHaveName()
    {
        $filename = $this->exampleFile(self::EXAMPLE_SINGLE_MEMBERSHIP);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $this->assertCount(1, $popolo->memberships);
        $m = $popolo->memberships[0];
        $m->name;
    }

    public function testMembershipHasPersonIdAndOrganisationId()
    {
        $filename = $this->exampleFile(self::EXAMPLE_SINGLE_MEMBERSHIP);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $this->assertCount(1, $popolo->memberships);
        $m = $popolo->memberships[0];
        $this->assertEquals('SP-937-215', $m->personId);
        $this->assertEquals('starfleet', $m->organizationId);
    }

    public function testMembershipHasRole()
    {
        $filename = $this->exampleFile(self::EXAMPLE_SINGLE_MEMBERSHIP);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $this->assertCount(1, $popolo->memberships);
        $m = $popolo->memberships[0];
        $this->assertEquals('student', $m->role);
    }

    public function testMembershipForeignKeys()
    {
        $filename = $this->exampleFile(self::EXAMPLE_MEMBERSHIP_ALL_FIELDS);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $this->assertCount(1, $popolo->memberships);
        $m = $popolo->memberships[0];
        $this->assertEquals('dunny-on-the-wold', $m->areaId);
        $this->assertEquals('adder', $m->onBehalfOfId);
        $this->assertEquals('pitt', $m->legislativePeriodId);
        $this->assertEquals('dunny-on-the-wold-seat', $m->postId);
    }

    public function testGetOrganizationFromMembership()
    {
        $filename = $this->exampleFile(self::EXAMPLE_SINGLE_MEMBERSHIP);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $this->assertCount(1, $popolo->memberships);
        $m = $popolo->memberships[0];
        $this->assertEquals(new \DateTime('2327-12-01'), $m->startDate);
    }

    // public function testGetSentinelEndDateFromMembership()
    // {
    //     $filename = $this->exampleFile(self::EXAMPLE_SINGLE_MEMBERSHIP);
    //     $popolo = Popolo::fromFilename($filename);
    //     unlink($filename);

    //     $this->assertCount(1, $popolo->memberships);
    //     $m = $popolo->memberships[0];
    //     $this->assertTrue($m->endDate->future);
    // }

    public function testOrganizationToString()
    {
        $filename = $this->exampleFile(self::EXAMPLE_SINGLE_MEMBERSHIP);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $this->assertCount(1, $popolo->memberships);
        $m = $popolo->memberships[0];
        $this->assertEquals("<Membership: 'SP-937-215' at 'starfleet'>", (string) $m);
    }

    public function testEqualityOfMemberships()
    {
        // Create the same membership via two Popolo objects - they
        // should still be equal.
        $filename = $this->exampleFile(self::EXAMPLE_SINGLE_MEMBERSHIP);
        $popoloA = Popolo::fromFilename($filename);
        $popoloB = Popolo::fromFilename($filename);
        unlink($filename);

        $this->assertCount(1, $popoloA->memberships);
        $mA = $popoloA->memberships[0];
        $this->assertCount(1, $popoloB->memberships);
        $mB = $popoloB->memberships[0];
        $this->assertTrue($mA->equals($mB));
    }

    public function testPersonMembershipsMethod()
    {
        $filename = $this->exampleFile(self::EXAMPLE_MULTIPLE_MEMBERSHIPS);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $person = $popolo->persons->first;
        $personMemberships = $person->memberships;
        $this->assertCount(2, $personMemberships);
        $this->assertTrue($popolo->memberships[0]->equals($personMemberships[0]));
        $this->assertTrue($popolo->memberships[1]->equals($personMemberships[1]));
    }

    public function testMembershipPersonMethod()
    {
        $filename = $this->exampleFile(self::EXAMPLE_MULTIPLE_MEMBERSHIPS);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $personPicard = $popolo->persons[0];
        $m = $popolo->memberships[0];
        $this->assertTrue($m->person->equals($personPicard));
    }

    public function testMembershipOrganizationMethod()
    {
        $filename = $this->exampleFile(self::EXAMPLE_MULTIPLE_MEMBERSHIPS);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $orgStarfleet = $popolo->organizations->first;
        $m = $popolo->memberships[0];
        $this->assertTrue($m->organization->equals($orgStarfleet));
    }

    public function testMembershipOnBehalfOfMethod()
    {
        $filename = $this->exampleFile(self::EXAMPLE_MEMBERSHIP_ALL_FIELDS);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $orgAdderParty = $popolo->organizations[1];
        $m = $popolo->memberships[0];
        $this->assertTrue($m->onBehalfOf->equals($orgAdderParty));
    }

    public function testMembershipAreaMethod()
    {
        $filename = $this->exampleFile(self::EXAMPLE_MEMBERSHIP_ALL_FIELDS);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $areaDunny = $popolo->areas[0];
        $m = $popolo->memberships[0];
        $this->assertTrue($m->area->equals($areaDunny));
    }

    public function testMembershipPostMethod()
    {
        $filename = $this->exampleFile(self::EXAMPLE_MEMBERSHIP_ALL_FIELDS);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $postDunny = $popolo->posts[0];
        $m = $popolo->memberships[0];
        $this->assertTrue($m->post->equals($postDunny));
    }

    public function testMembershipLegislativePeriod()
    {
        $filename = $this->exampleFile(self::EXAMPLE_MEMBERSHIP_ALL_FIELDS);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $eventPitt = $popolo->events[0];
        $m = $popolo->memberships[0];
        $this->assertTrue($m->legislativePeriod->equals($eventPitt));
    }

    public function testMembershipCurrentAtTrue()
    {
        $filename = $this->exampleFile(self::EXAMPLE_MEMBERSHIP_ALL_FIELDS);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $m = $popolo->memberships[0];
        $this->assertTrue($m->currentAt(new \DateTime('1784-04-30')));
    }

    public function testMembershipCurrentAtFalseBefore()
    {
        $filename = $this->exampleFile(self::EXAMPLE_MEMBERSHIP_ALL_FIELDS);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $m = $popolo->memberships[0];
        $this->assertFalse($m->currentAt(new \DateTime('1600-01-01')));
    }

    public function testMembershipCurrentAtFalseAfter()
    {
        $filename = $this->exampleFile(self::EXAMPLE_MEMBERSHIP_ALL_FIELDS);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $m = $popolo->memberships[0];
        $this->assertFalse($m->currentAt(new \DateTime('1800-01-01')));
    }

    // public function testMembershipCurrentTrue()
    // {
    //     // TODO: mock today's date to date(1784, 4, 30)
    //     $filename = $this->exampleFile(self::EXAMPLE_MEMBERSHIP_ALL_FIELDS);
    //     $popolo = Popolo::fromFilename($filename);
    //     unlink($filename);
    //
    //     $m = $popolo->memberships[0];
    //     $this->assertTrue($m->current);
    // }

    public function testHashMagicMethod()
    {
        // Not sure how to implement this test in PHP!
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testEqualityAndInequalityNotImplemented()
    {
        $filename = $this->exampleFile(self::EXAMPLE_MULTIPLE_MEMBERSHIPS);
        $popolo = Popolo::fromFilename($filename);
        unlink($filename);

        $m = $popolo->memberships->first;
        $this->assertFalse($m->equals('a string, not a person'));
    }
}
