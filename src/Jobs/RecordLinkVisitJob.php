<?php

namespace AusbildungMS\Shorty\Jobs;

use AusbildungMS\Shorty\Models\Link;
use AusbildungMS\Shorty\Models\LinkVisit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Wolfcast\BrowserDetection;

class RecordLinkVisitJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $link;
    private $additional;

    public function __construct(Link $link, array $additional)
    {
        $this->link = $link;
        $this->additional = $additional;
    }

    public function handle()
    {
        $visit_again = LinkVisit::where('hash', $this->additional['hash'])->exists();
        $browser = new BrowserDetection($this->additional['user_agent']);

        $this->link->visits()->create([
            'hash' => $this->additional['hash'],
            'is_recurring' => $visit_again,
            'browser_name' => $browser->getName(),
            'browser_version' => $browser->getVersion(),
            'platform_family' => $browser->getPlatform(),
            'platform_version' => $browser->getPlatformVersion(true),
            'platform_version_name' => $browser->getPlatformVersion(),
            'is_mobile' => $browser->isMobile(),
            'is_robot' => $browser->isRobot(),
            'robot_name' => $browser->getRobotName(),
            'robot_version' => $browser->getRobotVersion(),
        ]);

        if (! $browser->isRobot()) {
            $this->link->increment('total_visit_count');
        }

    }
}