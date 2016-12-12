<?php

namespace EveryPolitician\EveryPoliticianPopolo;

trait ExampleFileTrait
{
    private function exampleFile($contents)
    {
        $filename = tempnam(sys_get_temp_dir(), 'ep_');
        $fh = fopen($filename, 'w');
        fwrite($fh, $contents);
        return $filename;
    }
}
