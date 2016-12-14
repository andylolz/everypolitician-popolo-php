<?php

namespace EveryPolitician\EveryPoliticianPopolo\Collections;

use \Countable;
use \ArrayAccess;
use \Iterator;
use \EveryPolitician\EveryPoliticianPopolo\Exceptions;

class PopoloCollection implements Countable, ArrayAccess, Iterator
{
    protected $properties = [
        'first',
    ];
    public $lookupFromKey;
    private $objectClass;
    private $objectArr;
    private $position;

    /**
     * Creates a new instance
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

    /**
     * String representation of {@link PopoloCollection}
     *
     * @return string
     */
    public function __toString()
    {
        return '<'.get_class($this).'>';
    }

    /**
     * Getter for public attributes
     *
     * @param string $prop the attribute to get
     *
     * @return mixed
     */
    public function __get($prop)
    {
        if (in_array($prop, $this->properties)) {
            $getter = 'get'.ucfirst($prop);
            return $this->$getter();
        }
        trigger_error('Undefined property: '.__CLASS__.'::$'.$prop, E_USER_ERROR);
    }

    /**
     * Get the first item in this collection
     *
     * @return mixed
     */
    private function getFirst()
    {
        return (count($this->objectArr) > 0) ? $this->objectArr[0] : null;
    }

    /**
     * Gets an array of matching items, according to the
     * array of filters provided. Filters are key-value pairs
     * that are ANDed together, i.e. returned items match all
     * the criteria
     *
     * @param string[] $filters key-value set of criteria
     *
     * @return object[]
     */
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

    /**
     * Gets a single matching item, according to the
     * array of filters provided. Filters are key-value pairs
     * that are ANDed together, i.e. returned items match all
     * the criteria. If more or less than one item matches,
     * an exception is thrown
     *
     * @param string[] $filters key-value set of criteria
     *
     * @return object
     */
    public function get($filters)
    {
        $matches = $this->filter($filters);
        $n = count($matches);
        if ($n == 0) {
            $msg = "No ".$this->objectClass." found matching ".json_encode($filters);
            throw new Exceptions\ObjectDoesNotExistException($msg);
        } elseif ($n > 1) {
            $msg = "Multiple ".$this->objectClass." objects ($n) found matching ".json_encode($filters);
            throw new Exceptions\MultipleObjectsReturnedException($msg);
        }
        return $matches[0];
    }

    /**
     * Count elements of an object
     *
     * Part of the Countable interface
     *
     * @return int
     */
    public function count()
    {
        return count($this->objectArr);
    }

    /**
     * Checks if current position is valid
     *
     * Part of the ArrayAccess interface
     *
     * @param mixed $offset array offset
     *
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->objectArr);
    }

    /**
     * Offset to retrieve
     *
     * Part of the ArrayAccess interface
     *
     * @param mixed $offset array offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->objectArr[$offset];
    }

    /**
     * Assign a value to the specified offset
     *
     * Part of the ArrayAccess interface
     *
     * @param mixed $offset array offset
     * @param mixed $value value to assign
     */
    public function offsetSet($offset, $value)
    {
        $this->objectArr[$offset] = $value;
    }

    /**
     * Unset an offset
     *
     * Part of the ArrayAccess interface
     *
     * @param mixed $offset array offset
     */
    public function offsetUnset($offset)
    {
        unset($this->objectArr[$offset]);
    }

    /**
     * Return the current element
     *
     * Part of the Iterator interface
     *
     * @return mixed
     */
    public function current()
    {
        return $this->objectArr[$this->position];
    }

    /**
     * Return the key of the current element
     *
     * Part of the Iterator interface
     *
     * @return scalar
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Move forward to next element
     *
     * Part of the Iterator interface
     */
    public function next()
    {
        $this->position += 1;
    }

    /**
     * Rewind the Iterator to the first element
     *
     * Part of the Iterator interface
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * Checks if current position is valid
     *
     * Part of the Iterator interface
     *
     * @return boolean
     */
    public function valid()
    {
        return array_key_exists($this->position, $this->objectArr);
    }
}
