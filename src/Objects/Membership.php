<?php

namespace EveryPolitician\EveryPoliticianPopolo\Objects;

class Membership extends PopoloObject
{
    protected $properties = [
        'role',
        'personId',
        'person',
        'organizationId',
        'organization',
        'areaId',
        'area',
        'legislativePeriodId',
        'legislativePeriod',
        'onBehalfOfId',
        'onBehalfOf',
        'postId',
        'post',
        'startDate',
        'endDate',
        'current',
    ];

    public function __toString()
    {
        return "<Membership: '".$this->personId."' at '".$this->organizationId."'>";
    }

    protected function getRole()
    {
        return $this->arrGet($this->data, 'role');
    }

    protected function getPersonId()
    {
        return $this->arrGet($this->data, 'person_id');
    }

    protected function getPerson()
    {
        return $this->allPopolo->persons->lookupFromKey[$this->personId];
    }

    protected function getOrganizationId()
    {
        return $this->arrGet($this->data, 'organization_id');
    }

    protected function getOrganization()
    {
        return $this->allPopolo->organizations->lookupFromKey[$this->organizationId];
    }

    protected function getAreaId()
    {
        return $this->arrGet($this->data, 'area_id');
    }

    protected function getArea()
    {
        return $this->allPopolo->areas->lookupFromKey[$this->areaId];
    }

    protected function getLegislativePeriodId()
    {
        return $this->arrGet($this->data, 'legislative_period_id');
    }

    protected function getLegislativePeriod()
    {
        return $this->allPopolo->events->lookupFromKey[$this->legislativePeriodId];
    }

    protected function getOnBehalfOfId()
    {
        return $this->arrGet($this->data, 'on_behalf_of_id');
    }

    protected function getOnBehalfOf()
    {
        return $this->allPopolo->organizations->lookupFromKey[$this->onBehalfOfId];
    }

    protected function getPostId()
    {
        return $this->arrGet($this->data, 'post_id');
    }

    protected function getPost()
    {
        return $this->allPopolo->posts->lookupFromKey[$this->postId];
    }

    protected function getStartDate()
    {
        // TODO: Mark uses his magical ApproxDate.PAST here
        return $this->getDate('start_date');
    }

    protected function getEndDate()
    {
        // TODO: Mark uses his magical ApproxDate.FUTURE here
        return $this->getDate('end_date');
    }

    protected function getKeyForHash()
    {
        $sortedData = $this->data;
        ksort($sortedData);
        return json_encode($sortedData);
    }

    protected function getCurrent()
    {
        return $this->currentAt(new \DateTime);
    }

    public function currentAt($when)
    {
        return ($when >= $this->startDate && $when <= $this->endDate);
    }

    public function equals($other)
    {
        if (is_object($other) && get_class($other) == get_class($this)) {
            return $this->data == $other->data;
        }
        // TODO: Throw a custom exception here
        throw new \BadMethodCallException;
    }
}
