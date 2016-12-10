<?php

namespace mySociety\EveryPoliticianPopolo\Collections;

class PersonCollection extends PopoloCollection
{
    public function __construct($dataArr, $allPopolo)
    {
        $objectClass = 'mySociety\\EveryPoliticianPopolo\\Objects\\Person';
        parent::__construct($dataArr, $objectClass, $allPopolo);
    }
}
