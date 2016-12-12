<?php

namespace EveryPolitician\EveryPoliticianPopolo\Objects;

class Organization extends PopoloObject
{
    protected $properties = [
        'id',
        'name',
        'wikidata',
        'classification',
        'image',
        'foundingDate',
        'dissolutionDate',
        'seats',
        'otherNames',
        'identifiers',
        'links',
    ];

    public function __toString()
    {
        return "<Organization: ".$this->name.">";
    }

    protected function getId()
    {
         return $this->arrGet($this->data, 'id');
    }

    protected function getName()
    {
         return $this->arrGet($this->data, 'name');
    }

    protected function getWikidata()
    {
        return $this->identifierValue('wikidata');
    }

    protected function getClassification()
    {
        return $this->arrGet($this->data, 'classification');
    }

    protected function getImage()
    {
        return $this->arrGet($this->data, 'image');
    }

    protected function getFoundingDate()
    {
        return $this->getDate('founding_date');
    }
    protected function getDissolutionDate()
    {
        return $this->getDate('dissolution_date');
    }
    protected function getSeats()
    {
        return $this->arrGet($this->data, 'seats');
    }
    protected function getOtherNames()
    {
        return $this->arrGet($this->data, 'other_names', []);
    }

    protected function getIdentifiers()
    {
         return $this->getRelatedObjectArr('identifiers');
    }

    protected function getLinks()
    {
        return $this->getRelatedObjectArr('links');
    }
}
