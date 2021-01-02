<?php

namespace AusbildungMS\Shorty\Actions\Request;

use AusbildungMS\Shorty\Models\Link;
use Illuminate\Http\Request;

class PrepareRequestData
{

    public function execute(Request $request, Link $link)
    {
        $hashData = [
            $request->ip(),
            $request->userAgent(),
            now()->dayOfYear(),
            $link->short
        ];
        return [
            'user_agent' => $request->userAgent(),
            'hash' => hash('sha256', implode('+', $hashData)),
            'referer' => $request->headers->get('referer'),
        ];
    }
}