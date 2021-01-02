<?php

namespace AusbildungMS\Shorty;

use AusbildungMS\Shorty\Models\Link;

trait HasLinks
{
    public function links()
    {
        return $this->morphMany(Link::class, 'owner');
    }
}