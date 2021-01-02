<?php

namespace AusbildungMS\Shorty\Tests\Models;

use AusbildungMS\Shorty\Models\{Domain, Link};
use AusbildungMS\Shorty\Tests\Helper\User;
use AusbildungMS\Shorty\Tests\TestCase;

class LinkTest extends TestCase
{
    /** @test */
    public function it_works() {
        $link = Link::factory()->create();

        $this->assertDatabaseHas($link->getTable(), [
            'short' => $link->short
        ]);
    }

    /** @test */
    public function it_can_belong_to_a_domain() {
        $link = Link::factory()
                    ->for(Domain::factory())
                    ->create();

        $this->assertNotNull($link->domain);
        $this->assertTrue($link->domain->links->contains('id', $link->id));
    }

    /** @test */
    public function it_can_belong_to_an_owner() {
        $user = User::create(['email' => 'test@email.com']);

        $link = $user->links()->create(Link::factory()->raw());

        $this->assertTrue($user->is($link->owner));
        $this->assertTrue($user->links->contains('id', $link->id));
    }
    
    /** @test */
    public function it_automatically_generates_a_short_code() {
        $link = Link::factory()->create(['short' => null]);
        $this->assertNotNull($link->short);
    }

    /** @test */
    public function it_has_a_shorthand() {
        $link = Link::createForDestination('https://google.com');

        $this->assertTrue($link instanceof Link);
        $this->assertDatabaseCount($link->getTable(), 1);
    }
}