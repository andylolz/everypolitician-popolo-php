<?php

namespace mySociety\EveryPoliticianPopolo\Collections;

class AreaCollection extends PopoloCollection
{
    public function __construct($dataArr, $allPopolo)
    {
        $objectClass = 'mySociety\\EveryPoliticianPopolo\\Objects\\Area';
        parent::__construct($dataArr, $objectClass, $allPopolo);
    }
}
