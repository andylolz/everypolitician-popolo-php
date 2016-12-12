<?php

namespace mySociety\EveryPoliticianPopolo\Objects;

class PopoloObject
{
    use \mySociety\EveryPoliticianPopolo\Traits\ArrayGetterTrait;

    protected $properties = [];
    protected $baseProperties = [
        'keyForHash',
    ];
    protected $data;
    protected $allPopolo;

    /**
     *
     */
    public function __construct($data, $allPopolo)
    {
        $this->data = $data;
        $this->allPopolo = $allPopolo;
    }

    public function __get($prop)
    {
        $properties = array_merge($this->baseProperties, $this->properties);
        if (in_array($prop, $properties)) {
            $getter = 'get' . ucfirst($prop);
            return $this->$getter();
        }
        trigger_error('Undefined property: '.__CLASS__.'::$'.$prop, E_USER_ERROR);
    }

    protected function getKeyForHash()
    {
        return $this->getId();
    }

    protected function getId()
    {
        return null;
    }

    private function getRelatedObjects($popoloArray)
    {
        return $this->arrGet($this->data, $popoloArray, []);
    }

    protected function getDate($attr, $default = null)
    {
        $d = $this->arrGet($this->data, $attr);
        if ($d) {
            return new \DateTime($d);
        }
        return $default;
    }

    private function getRelatedValues($popoloArray, $infoTypeKey, $infoType, $infoValueKey)
    {
        /* Get a value from one of the Popolo related objects
         *
         * For example, if you have a person with related links, like
         * this:
         *
         *     {
         *         "name": "Dale Cooper",
         *         "links": [
         *             {
         *                 "note": "wikipedia",
         *                 "url": "https://en.wikipedia.org/wiki/Dale_Cooper"
         *             }
         *         ]
         *     }
         *
         * When calling this method to get the Wikipedia URL, you would use:
         *
         *     popoloArray  = 'links'
         *     infoTypeKey  = 'note'
         *     infoType     = 'wikipedia'
         *     infoValueKey = 'url'
         *
         * ... so the following would work:
         *
         *     $this->getRelatedValue('links', 'note', 'wikipedia', 'url')
         *     # => 'https://en.wikipedia.org/wiki/Dale_Cooper'
         */
        $relatedValues = [];
        $relatedObjects = $this->getRelatedObjects($popoloArray);
        foreach ($relatedObjects as $o) {
            if ($o[$infoTypeKey] === $infoType) {
                $relatedValues[] = $o[$infoValueKey];
            }
        }
        return $relatedValues;
    }

    public function identifierValues($scheme)
    {
        return $this->getRelatedValues('identifiers', 'scheme', $scheme, 'identifier');
    }

    public function identifierValue($scheme)
    {
        $identifierValues = $this->identifierValues($scheme);
        return (count($identifierValues) > 0) ? $identifierValues[0] : null;
    }

    public function linkValues($note)
    {
        return $this->getRelatedValues('links', 'note', $note, 'url');
    }

    public function linkValue($note)
    {
        $linkValues = $this->linkValues($note);
        return (count($linkValues) > 0) ? $linkValues[0] : null;
    }

    public function contactDetailValues($contactType)
    {
        return $this->getRelatedValues('contact_details', 'type', $contactType, 'value');
    }

    public function contactDetailValue($contactType)
    {
        $contactDetailValues = $this->contactDetailValues($contactType);
        return (count($contactDetailValues) > 0) ? $contactDetailValues[0] : null;
    }

    public function equals($other)
    {
        if (is_object($other) && get_class($other) == get_class($this)) {
            return $this->id == $other->id;
        }
        throw new \BadMethodCallException;
    }

    public function getRelatedObjectArr($popoloArray)
    {
        return $this->arrGet($this->data, $popoloArray, []);
    }
}
