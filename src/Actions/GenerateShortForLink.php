<?php

namespace AusbildungMS\Shorty\Actions;

use AusbildungMS\Shorty\Models\Link;
use Illuminate\Support\Str;

class GenerateShortForLink
{
    public function execute()
    {
        $str = Str::random(rand(6,9));

        while (Link::where('short', $str)->exists()) {
            $str = Str::random(rand(6,9));
        }

        return $str;
    }
}