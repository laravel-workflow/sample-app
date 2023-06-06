<?php

namespace App\Traits;

trait HasUuid
{
    public function getKeyType()
    {
        return 'string';
    }

    public function getIncrementing()
    {
        return false;
    }
}
