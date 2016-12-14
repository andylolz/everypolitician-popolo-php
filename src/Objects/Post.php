<?php

namespace EveryPolitician\EveryPoliticianPopolo\Objects;

class Post extends PopoloObject
{
    protected $properties = [
        'id',
        'label',
        'organizationId',
        'organization',
    ];

    /**
     * String representation of {@link Post}
     *
     * @return string
     */
    public function __toString()
    {
        return "<Post: ".$this->label.">";
    }

    protected function getId()
    {
         return $this->arrGet($this->data, 'id');
    }

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
