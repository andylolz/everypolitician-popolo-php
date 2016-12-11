<?php

namespace mySociety\EveryPoliticianPopolo;

class Parse
{
    public static function extractTwitterUsername($usernameOrUrl)
    {
        $splitUrl = parse_url($usernameOrUrl);
        if (array_key_exists('host', $splitUrl) && $splitUrl['host'] == 'twitter.com') {
            return preg_replace('!^/([^/]+).*!', '\1', $splitUrl['path']);
        }
        return ltrim(trim($usernameOrUrl), '@');
    }
}
