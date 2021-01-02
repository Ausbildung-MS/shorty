<?php

namespace AusbildungMS\Shorty\Database\Factories;
use AusbildungMS\Shorty\Models\Link;
use \Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class LinkFactory extends Factory
{
    protected $model = Link::class;

    public function definition()
    {
        return [
            'uuid' => $this->faker->uuid,
            'destination' => $this->faker->url,
            'short' => Str::random()
        ];
    }
}