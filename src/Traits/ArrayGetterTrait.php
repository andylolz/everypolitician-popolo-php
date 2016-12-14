<?php

namespace EveryPolitician\EveryPoliticianPopolo\Traits;

trait ArrayGetterTrait
{
    /**
     * Fetch a value from an array by key, or return
     * a default if the key is not set.
     *
     * Analogous to python's `dict.get(key, default=None)`
     *
     * @param mixed[] $arr the array to be queried
     * @param (int|string) $key the key to look up
     * @param mixed $default the default return value
     *
     * @return mixed
     */
    public function arrGet($arr, $key, $default = null)
    {
        return array_key_exists($key, $arr) ? $arr[$key] : $default;
    }
}
