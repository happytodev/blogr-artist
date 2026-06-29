<?php

namespace Happytodev\BlogrArtist\Database\Factories;

use Happytodev\BlogrArtist\Models\Artwork;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArtworkFactory extends Factory
{
    protected $model = Artwork::class;

    public function definition(): array
    {
        return [
            'is_published' => true,
            'sort_order' => 0,
            'published_at' => now(),
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attrs) => [
            'is_published' => true,
            'published_at' => now(),
        ]);
    }
}
