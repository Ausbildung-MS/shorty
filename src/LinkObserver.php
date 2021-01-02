<?php

namespace AusbildungMS\Shorty;

use AusbildungMS\Shorty\Models\Link;

class LinkObserver
{
    public function creating(Link $link)
    {
        if (! $link->short) {
            $action = config('shorty.actions.generateShort');
            $link->short = (new $action)->execute();
        }
    }
}