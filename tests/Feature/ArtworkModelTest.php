<?php

use Happytodev\BlogrArtist\Models\Artwork;
use Happytodev\BlogrArtist\Tests\TestCase;

uses(TestCase::class);

test('can create an artwork with translation', function () {
    $artwork = Artwork::factory()->create([
        'is_published' => true,
        'published_at' => now(),
    ]);

    $translation = $artwork->translations()->create([
        'locale' => 'en',
        'title' => 'Sunset Portrait',
        'slug' => 'sunset-portrait',
        'description' => 'A beautiful sunset portrait.',
        'price' => '150€',
        'category_name' => 'Portrait',
        'is_available' => true,
    ]);

    expect($artwork->is_published)->toBeTrue();
    expect($artwork->translations)->toHaveCount(1);
    expect($translation->title)->toBe('Sunset Portrait');
    expect($translation->slug)->toBe('sunset-portrait');
    expect($translation->price)->toBe('150€');
});

test('published scope only returns published artworks', function () {
    Artwork::factory()->create(['is_published' => true, 'published_at' => now()]);
    Artwork::factory()->create(['is_published' => false]);

    $published = Artwork::published()->get();

    expect($published)->toHaveCount(1);
});

test('featured scope only returns featured artworks', function () {
    Artwork::factory()->featured()->create();
    Artwork::factory()->create();

    $featured = Artwork::featured()->get();

    expect($featured)->toHaveCount(1);
});

test('translation has unique slug per locale', function () {
    $artwork1 = Artwork::factory()->create();
    $artwork1->translations()->create([
        'locale' => 'en', 'title' => 'Sunset', 'slug' => 'sunset',
    ]);

    $this->expectException(\Illuminate\Database\QueryException::class);

    $artwork2 = Artwork::factory()->create();
    $artwork2->translations()->create([
        'locale' => 'en', 'title' => 'Sunset Dup', 'slug' => 'sunset',
    ]);
});

test('artworks can be ordered by sort_order', function () {
    Artwork::factory()->create(['sort_order' => 3]);
    Artwork::factory()->create(['sort_order' => 1]);
    Artwork::factory()->create(['sort_order' => 2]);

    $sorted = Artwork::query()->orderBy('sort_order')->get();

    expect($sorted[0]->sort_order)->toBeLessThan($sorted[1]->sort_order);
    expect($sorted[1]->sort_order)->toBeLessThan($sorted[2]->sort_order);
});
