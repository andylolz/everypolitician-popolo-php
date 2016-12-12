<?php

namespace EveryPolitician\EveryPoliticianPopolo;

use \EveryPolitician\EveryPoliticianPopolo\Parse;

class TwitterTest extends \PHPUnit_Framework_TestCase
{
    public function testStripWhitespace()
    {
        $result = Parse::extractTwitterUsername('  everypolitbot  ');
        $this->assertEquals('everypolitbot', $result);
    }

    public function testRemoveExtraneousAtPrefix()
    {
        $result = Parse::extractTwitterUsername('@everypolitbot');
        $this->assertEquals('everypolitbot', $result);
    }

    public function testExtractUsernameFromTwitterUrl()
    {
        $result = Parse::extractTwitterUsername('https://twitter.com/everypolitbot');
        $this->assertEquals('everypolitbot', $result);
    }

    public function testExtractUsernameFromTwitterUrlWithTrailingSlash()
    {
        $result = Parse::extractTwitterUsername('https://twitter.com/everypolitbot/');
        $this->assertEquals('everypolitbot', $result);
    }
}
