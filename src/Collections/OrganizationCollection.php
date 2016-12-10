<?php

namespace mySociety\EveryPoliticianPopolo\Collections;

class OrganizationCollection extends PopoloCollection
{
    public function __construct($dataArr, $allPopolo)
    {
        $objectClass = 'mySociety\\EveryPoliticianPopolo\\Objects\\Organization';
        parent::__construct($dataArr, $objectClass, $allPopolo);
    }
}
