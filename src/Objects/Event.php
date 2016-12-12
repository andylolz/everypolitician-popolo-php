<?php

namespace mySociety\EveryPoliticianPopolo\Objects;

class Event extends PopoloObject
{
    protected $properties = [
        'id',
        'name',
        'classification',
        'startDate',
        'endDate',
        'organizationId',
        'organization',
        'identifiers',
        'current',
    ];

    public function __toString()
    {
        return "<Event: ".$this->name.">";
    }

    protected function getId()
    {
        return $this->arrGet($this->data, 'id');
    }

    protected function getName()
    {
        return $this->arrGet($this->data, 'name');
    }

    protected function getClassification()
    {
        return $this->arrGet($this->data, 'classification');
    }

    protected function getStartDate()
    {
        // TODO: Mark uses his magical ApproxDate.PAST here
        return $this->getDate('start_date', null);
    }

    protected function getEndDate()
    {
        // TODO: Mark uses his magical ApproxDate.FUTURE here
        return $this->getDate('end_date', null);
    }

    protected function getOrganizationId()
    {
        return $this->arrGet($this->data, 'organization_id');
    }

    protected function getOrganization()
    {
        return $this->allPopolo->organizations->lookupFromKey[$this->organizationId];
    }

    protected function getIdentifiers()
    {
        return $this->getRelatedObjectArr('identifiers');
    }

    protected function getCurrent()
    {
        return $this->currentAt(new \DateTime);
    }

    public function currentAt($when)
    {
        return ($when >= $this->startDate && $when <= $this->endDate);
    }
}
