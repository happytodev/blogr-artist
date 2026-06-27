<?php

use Happytodev\BlogrArtist\Models\Artwork;
use Happytodev\BlogrArtist\Tests\TestCase;
use Illuminate\Support\Facades\Storage;

uses(TestCase::class);

beforeEach(function () {
    Storage::fake('public');
});

test('portfolio route exists', function () {
    $routes = collect(app('router')->getRoutes())->map(fn ($r) => $r->uri() . ' (' . $r->getName() . ')');
    expect($routes)->toContain('portfolio (artist.portfolio.index)');
});

test('portfolio index returns 200 with artworks', function () {
    $artwork = Artwork::factory()->create(['is_published' => true, 'published_at' => now()]);
    $artwork->translations()->create([
        'locale' => 'en',
        'title' => 'Test Artwork',
        'slug' => 'test-artwork',
        'description' => 'A test artwork.',
    ]);

    $response = $this->get(route('artist.portfolio.index'));

    $response->assertStatus(200);
    $response->assertSee('Test Artwork');
});

test('portfolio index does not show unpublished artworks', function () {
    $artwork = Artwork::factory()->create(['is_published' => false]);
    $artwork->translations()->create([
        'locale' => 'en',
        'title' => 'Hidden Artwork',
        'slug' => 'hidden-artwork',
    ]);

    $response = $this->get(route('artist.portfolio.index'));

    $response->assertStatus(200);
    $response->assertDontSee('Hidden Artwork');
});

test('portfolio show returns 200 for published artwork', function () {
    $artwork = Artwork::factory()->create(['is_published' => true, 'published_at' => now()]);
    $artwork->translations()->create([
        'locale' => 'en',
        'title' => 'My Artwork',
        'slug' => 'my-artwork',
        'description' => 'Detailed description.',
        'price' => '200€',
    ]);

    $response = $this->get(route('artist.portfolio.show', 'my-artwork'));

    $response->assertStatus(200);
    $response->assertSee('My Artwork');
    $response->assertSee('200€');
    $response->assertSee('Detailed description.');
});

test('portfolio show returns 404 for unpublished artwork', function () {
    $artwork = Artwork::factory()->create(['is_published' => false]);
    $artwork->translations()->create([
        'locale' => 'en',
        'title' => 'Hidden',
        'slug' => 'hidden',
    ]);

    $response = $this->get(route('artist.portfolio.show', 'hidden'));

    $response->assertStatus(404);
});

test('portfolio show returns 404 for non-existent slug', function () {
    $response = $this->get(route('artist.portfolio.show', 'non-existent'));

    $response->assertStatus(404);
});
