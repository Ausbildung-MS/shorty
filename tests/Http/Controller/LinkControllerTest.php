<?php

namespace AusbildungMS\Shorty\Tests\Http\Controller;

use AusbildungMS\Shorty\Models\Domain;
use AusbildungMS\Shorty\Models\Link;
use AusbildungMS\Shorty\Tests\TestCase;
use Illuminate\Support\Str;

class LinkControllerTest extends TestCase
{
    /** @test */
    public function it_redirects_correctly() {

        $link = Link::factory()->create();

        $this->get('http://' . $link->fullUrl)->assertRedirect($link->destination);

        $this->assertCount(1, $link->fresh()->visits);
    }

    /** @test */
    public function it_only_redirects_to_correct_domain() {

        $link = Link::factory()
            ->for(Domain::factory())
            ->create();

        $this->get('http://' . Str::replaceFirst('{link:short}', $link->short, config('shorty.root_route')))->assertNotFound();
        $this->get('http://' . $link->fullUrl)->assertRedirect($link->destination);
    }
}