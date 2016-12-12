<?php

namespace EveryPolitician\EveryPoliticianPopolo\Objects;

use \EveryPolitician\EveryPoliticianPopolo\Parse;

class Person extends PopoloObject
{
    use \EveryPolitician\EveryPoliticianPopolo\Traits\ArrayGetterTrait;

    protected $properties = [
        'id',
        'email',
        'gender',
        'honorificPrefix',
        'honorificSuffix',
        'image',
        'name',
        'sortName',
        'nationalIdentity',
        'summary',
        'biography',
        'birthDate',
        'deathDate',
        'familyName',
        'givenName',
        'wikidata',
        'twitter',
        'twitterAll',
        'phone',
        'phoneAll',
        'facebook',
        'facebookAll',
        'fax',
        'faxAll',

        'links',
        'contactDetails',
        'identifiers',
        'images',
        'otherNames',
        'sources',
        'memberships',
    ];

    public function __toString()
    {
        return "<Person: ".$this->name.">";
    }

    protected function getId()
    {
         return $this->arrGet($this->data, 'id');
    }

    protected function getEmail()
    {
         return $this->arrGet($this->data, 'email');
    }

    protected function getGender()
    {
         return $this->arrGet($this->data, 'gender');
    }

    protected function getHonorificPrefix()
    {
         return $this->arrGet($this->data, 'honorific_prefix');
    }

    protected function getHonorificSuffix()
    {
         return $this->arrGet($this->data, 'honorific_suffix');
    }

    protected function getImage()
    {
         return $this->arrGet($this->data, 'image');
    }

    protected function getName()
    {
         return $this->arrGet($this->data, 'name');
    }

    protected function getSortName()
    {
         return $this->arrGet($this->data, 'sort_name');
    }

    protected function getNationalIdentity()
    {
         return $this->arrGet($this->data, 'national_identity');
    }

    protected function getSummary()
    {
         return $this->arrGet($this->data, 'summary');
    }

    protected function getBiography()
    {
         return $this->arrGet($this->data, 'biography');
    }

    protected function getBirthDate()
    {
        return $this->getDate('birth_date');
    }

    protected function getDeathDate()
    {
        return $this->getDate('death_date');
    }

    protected function getFamilyName()
    {
         return $this->arrGet($this->data, 'family_name');
    }

    protected function getGivenName()
    {
         return $this->arrGet($this->data, 'given_name');
    }

    protected function getWikidata()
    {
        return $this->identifierValue('wikidata');
    }

    protected function getTwitter()
    {
        $usernameOrUrl = $this->contactDetailValue('twitter') ?: $this->linkValue('twitter');
        if ($usernameOrUrl) {
            return Parse::extractTwitterUsername($usernameOrUrl);
        }
        return null;
    }

    protected function getTwitterAll()
    {
        // The Twitter screen names in contact_details and links will
        // in most cases be the same, so remove duplicates:
        $allTwitters = [];
        $rawTwitters = $this->contactDetailValues('twitter') + $this->linkValues('twitter');
        foreach ($rawTwitters as $rawTwitter) {
            $twitter = Parse::extractTwitterUsername($rawTwitter);
            if (!in_array($twitter, $allTwitters)) {
                $allTwitters[] = $twitter;
            }
        }
        return $allTwitters;
    }

    protected function getPhone()
    {
        return $this->contactDetailValue('phone');
    }

    protected function getPhoneAll()
    {
        return $this->contactDetailValues('phone');
    }

    protected function getFacebook()
    {
        return $this->linkValue('facebook');
    }

    protected function getFacebookAll()
    {
        return $this->linkValues('facebook');
    }

    protected function getFax()
    {
        return $this->contactDetailValue('fax');
    }

    protected function getFaxAll()
    {
        return $this->contactDetailValues('fax');
    }

    protected function getLinks()
    {
         return $this->getRelatedObjectArr('links');
    }

    protected function getContactDetails()
    {
         return $this->getRelatedObjectArr('contact_details');
    }

    protected function getIdentifiers()
    {
         return $this->getRelatedObjectArr('identifiers');
    }

    protected function getImages()
    {
         return $this->getRelatedObjectArr('images');
    }

    protected function getOtherNames()
    {
         return $this->getRelatedObjectArr('other_names');
    }

    protected function getSources()
    {
         return $this->getRelatedObjectArr('sources');
    }

    protected function getMemberships()
    {
        $memberships = [];
        foreach ($this->allPopolo->memberships as $m) {
            if ($m->personId == $this->id) {
                $memberships[] = $m;
            }
        }
        return $memberships;
    }

    private function isNameCurrentAt($name, $dateStr)
    {
        $startRange = $this->arrGet($name, 'start_date') ?: '0001-01-01';
        $endRange = $this->arrGet($name, 'end_date') ?: '9999-12-31';
        return $dateStr >= $startRange && $dateStr <= $endRange;
    }

    public function nameAt($particularDate)
    {
        $historicNames = [];
        foreach ($this->otherNames as $otherName) {
            if (array_key_exists('end_date', $otherName)) {
                $historicNames[] = $otherName;
            }
        }
        if (empty($historicNames)) {
            return $this->name;
        }
        $namesAtDate = [];
        foreach ($historicNames as $n) {
            if ($this->isNameCurrentAt($n, $particularDate->format('Y-m-d'))) {
                $namesAtDate[] = $n;
            }
        }
        if (empty($namesAtDate)) {
            return $this->name;
        }
        if (count($namesAtDate) > 1) {
            $msg = 'Multiple names for ' . (string) $this;
            $msg .= ' found at date ' . $particularDate->format('Y-m-d');
            throw new \Exception($msg);
        }
        return $namesAtDate[0]['name'];
    }
}
