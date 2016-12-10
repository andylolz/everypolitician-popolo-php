<?php

namespace mySociety\EveryPoliticianPopolo\Collections;

class EventCollection extends PopoloCollection
{
    public function __construct($dataArr, $allPopolo)
    {
        $objectClass = 'mySociety\\EveryPoliticianPopolo\\Objects\\Event';
        parent::__construct($dataArr, $objectClass, $allPopolo);
    }
}
