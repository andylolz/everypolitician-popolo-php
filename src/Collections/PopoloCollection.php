<?php

namespace mySociety\EveryPoliticianPopolo\Collections;

use \mySociety\EveryPoliticianPopolo\Exceptions;

class PopoloCollection implements \Countable, \ArrayAccess, \Iterator
{
    protected $properties = [
        'first',
    ];
    public $lookupFromKey;
    private $objectClass;
    private $objectArr;
    private $position;

    /**
     *
     */
    public function __construct($dataArr, $objectClass, $allPopolo)
    {
        $this->position = 0;
        $this->objectClass = $objectClass;
        $this->objectArr = [];
        foreach ($dataArr as $data) {
            $this->objectArr[] = new $objectClass($data, $allPopolo);
        }
        $this->lookupFromKey = [];
        foreach ($this->objectArr as $o) {
            $this->lookupFromKey[$o->keyForHash] = $o;
        }
    }

    public function __toString()
    {
        return '<'.get_class($this).'>';
    }

    public function __get($prop)
    {
        if (in_array($prop, $this->properties)) {
            $getter = 'get' . ucfirst($prop);
            return $this->$getter();
        }
        trigger_error('Undefined property: '.__CLASS__.'::$'.$prop, E_USER_ERROR);
    }

    private function getFirst()
    {
        return (count($this->objectArr) > 0) ? $this->objectArr[0] : null;
    }

    public function filter($filters)
    {
        $filtered = [];
        foreach ($this->objectArr as $obj) {
            $success = true;
            foreach ($filters as $prop => $value) {
                if ($obj->$prop !== $value) {
                    $success = false;
                    break;
                }
            }
            if ($success) {
                $filtered[] = $obj;
            }
        }
        return $filtered;
    }

    public function get($filters)
    {
        $matches = $this->filter($filters);
        $n = count($matches);
        if ($n == 0) {
            $msg = "No " . $this->objectClass . " found matching " . json_encode($filters);
            throw new Exceptions\ObjectDoesNotExistException($msg);
        } elseif ($n > 1) {
            $msg = "Multiple " . $this->objectClass . " objects ($n) found matching " . json_encode($filters);
            throw new Exceptions\MultipleObjectsReturnedException($msg);
        }
        return $matches[0];
    }

    // Countable interface
    public function count()
    {
        return count($this->objectArr);
    }

    // ArrayAccess interface
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->objectArr);
    }

    // ArrayAccess interface
    public function offsetGet($offset)
    {
        return $this->objectArr[$offset];
    }

    // ArrayAccess interface
    public function offsetSet($offset, $value)
    {
        $this->objectArr[$offset] = $value;
    }

    // ArrayAccess interface
    public function offsetUnset($offset)
    {
        unset($this->objectArr[$offset]);
    }

    // Iterator interface
    public function current()
    {
        return $this->objectArr[$this->position];
    }

    // Iterator interface
    public function key()
    {
        return $this->position;
    }

    // Iterator interface
    public function next()
    {
        $this->position += 1;
    }

    // Iterator interface
    public function rewind()
    {
        $this->position = 0;
    }

    // Iterator interface
    public function valid()
    {
        return array_key_exists($this->position, $this->objectArr);
    }
}
