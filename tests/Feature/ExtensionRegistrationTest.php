<?php

use Happytodev\Blogr\Services\ExtensionRegistry;
use Happytodev\BlogrArtist\BlogrArtistPlugin;
use Happytodev\BlogrArtist\Tests\TestCase;

uses(TestCase::class);

test('blogr-artist is registered as an extension', function () {
    $registry = app(ExtensionRegistry::class);

    expect($registry->has('blogr-artist'))->toBeTrue();
});

test('blogr-artist extension has correct metadata', function () {
    $registry = app(ExtensionRegistry::class);
    $extension = $registry->get('blogr-artist');

    expect($extension)->toBeInstanceOf(BlogrArtistPlugin::class);
    expect($extension->getName())->toBe('Artist Portfolio');
    expect($extension->getVersion())->toBe('1.0.0');
    expect($extension->getAuthor())->toBe('HappyToDev');
    expect($extension->getDependencies())->toContain('blogr-core');
});

test('blogr-artist is enabled by default', function () {
    $registry = app(ExtensionRegistry::class);

    expect($registry->isEnabled('blogr-artist'))->toBeTrue();
});
