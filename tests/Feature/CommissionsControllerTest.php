<?php

use Happytodev\BlogrArtist\Models\Artwork;
use Happytodev\BlogrArtist\Tests\TestCase;
use Illuminate\Support\Facades\Storage;

uses(TestCase::class);

beforeEach(function () {
    Storage::fake('public');
});

test('commissions index returns 200 with artworks', function () {
    $artwork = Artwork::factory()->create([
        'is_published' => true,
        'published_at' => now(),
        'show_in_commissions' => true,
    ]);
    $artwork->translations()->create([
        'locale' => 'en',
        'title' => 'Commission Artwork',
        'slug' => 'commission-artwork',
        'price' => '100€',
        'image' => 'artworks/comm.jpg',
    ]);
    Storage::disk('public')->put('artworks/comm.jpg', 'fake');

    $response = $this->get(route('artist.commissions.index'));

    $response->assertStatus(200);
    $response->assertSee('Commission Artwork');
    $response->assertSee('100€');
});

test('commissions does not show artworks not marked for commissions', function () {
    $artwork = Artwork::factory()->create([
        'is_published' => true,
        'published_at' => now(),
        'show_in_commissions' => false,
    ]);
    $artwork->translations()->create([
        'locale' => 'en',
        'title' => 'Hidden From Commissions',
        'slug' => 'hidden',
    ]);

    $response = $this->get(route('artist.commissions.index'));

    $response->assertStatus(200);
    $response->assertDontSee('Hidden From Commissions');
});

test('commissions shows x-data carousel attributes', function () {
    Artwork::factory()->count(3)->create([
        'is_published' => true,
        'published_at' => now(),
        'show_in_commissions' => true,
    ])->each(fn ($a) => $a->translations()->create([
        'locale' => 'en', 'title' => 'Comm ' . $a->id, 'slug' => 'comm-' . $a->id,
    ]));

    $response = $this->get(route('artist.commissions.index'));

    $response->assertStatus(200);
    $response->assertSee('currentSlide');
    $response->assertSee('autoplayInterval');
});

test('commissions returns 200 when empty', function () {
    $response = $this->get(route('artist.commissions.index'));

    $response->assertStatus(200);
});
