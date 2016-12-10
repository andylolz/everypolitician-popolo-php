<?php

namespace mySociety\EveryPoliticianPopolo\Collections;

class MembershipCollection extends PopoloCollection
{
    public function __construct($dataArr, $allPopolo)
    {
        $objectClass = 'mySociety\\EveryPoliticianPopolo\\Objects\\Membership';
        parent::__construct($dataArr, $objectClass, $allPopolo);
    }
}
