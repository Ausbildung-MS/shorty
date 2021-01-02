<?php

namespace AusbildungMS\Shorty\Database\Factories;

use AusbildungMS\Shorty\Models\Domain;
use \Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DomainFactory extends Factory
{
    protected $model = Domain::class;

    public function definition()
    {
        return [
            'uuid' => $this->faker->uuid,
            'domain' => strtolower(Str::random() . '.com'),
        ];
    }
}