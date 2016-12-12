<?php

namespace EveryPolitician\EveryPoliticianPopolo\Objects;

class Area extends PopoloObject
{
    protected $properties = [
        'id',
        'name',
        'type',
        'identifiers',
        'otherNames',
        'wikidata',
    ];

    public function __toString()
    {
        return "<Area: ".$this->name.">";
    }

    protected function getId()
    {
         return $this->arrGet($this->data, 'id');
    }

    protected function getName()
    {
         return $this->arrGet($this->data, 'name');
    }

    protected function getType()
    {
         return $this->arrGet($this->data, 'type');
    }

    protected function getIdentifiers()
    {
         return $this->getRelatedObjectArr('identifiers');
    }

    protected function getOtherNames()
    {
         return $this->getRelatedObjectArr('other_names');
    }

    protected function getWikidata()
    {
        return $this->identifierValue('wikidata');
    }
}
