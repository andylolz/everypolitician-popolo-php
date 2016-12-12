<?php

namespace EveryPolitician\EveryPoliticianPopolo\Traits;

trait ArrayGetterTrait
{
    public function arrGet($arr, $key, $default = null)
    {
        return array_key_exists($key, $arr) ? $arr[$key] : $default;
    }
}
