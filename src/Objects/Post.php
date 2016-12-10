<?php

namespace mySociety\EveryPoliticianPopolo\Objects;

class Post extends PopoloObject
{
    protected $properties = [
        'label',
        'organizationId',
        'organization',
    ];

    protected function getLabel()
    {
         return $this->arrGet($this->data, 'label');
    }

    protected function getOrganizationId()
    {
        return $this->arrGet($this->data, 'organization_id');
    }

    protected function getOrganization()
    {
        return $this->allPopolo->organizations->lookupFromKey[$this->organizationId];
    }
}
