<?php

namespace mySociety\EveryPoliticianPopolo\Collections;

class PostCollection extends PopoloCollection
{
    public function __construct($dataArr, $allPopolo)
    {
        $objectClass = 'mySociety\\EveryPoliticianPopolo\\Objects\\Post';
        parent::__construct($dataArr, $objectClass, $allPopolo);
    }
}
