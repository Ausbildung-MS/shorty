<?php

namespace AusbildungMS\Shorty\Http\Controller;

use AusbildungMS\Shorty\Jobs\RecordLinkVisitJob;
use AusbildungMS\Shorty\Models\Link;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class LinkController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function redirect($link, Request $request)
    {
        return $this->redirectExtern(config('shorty.root_domain'), $link, $request);
    }

    public function redirectExtern($domain, $link, Request $request)
    {
        $action = config('shorty.actions.PrepareRequestData');

        $link = Link::query()
            ->when($domain != config('shorty.root_domain'), function($q) use ($domain) {
                $q->whereHas('domain', function($q) use ($domain) {
                    return $q->where('domain', strtolower($domain));
                });
            })
            ->when($domain == config('shorty.root_domain'), function($q) use ($domain) {
                $q->whereNull('domain_id');
            })
            ->where('short', $link)
            ->firstOrFail();

        RecordLinkVisitJob::dispatchAfterResponse($link, (new $action)->execute($request, $link));

        return redirect($link->destination, $link->redirect_status);

    }
}