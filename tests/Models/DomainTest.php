<?php

namespace AusbildungMS\Shorty\Tests\Models;

use AusbildungMS\Shorty\Models\Domain;
use AusbildungMS\Shorty\Tests\TestCase;

class DomainTest extends TestCase
{
    /** @test */
    public function it_works() {
        $domain = Domain::factory()->create();
        $this->assertDatabaseHas($domain->getTable(), [
            'domain' => $domain->domain
        ]);
    }
}