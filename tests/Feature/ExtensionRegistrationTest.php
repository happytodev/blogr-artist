<?php

use Filament\Panel;
use Happytodev\Blogr\Contracts\BlogrExtension;
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

test('blogr-artist implements BlogrExtension interface', function () {
    $plugin = app(BlogrArtistPlugin::class);

    expect($plugin)->toBeInstanceOf(BlogrExtension::class);
    expect($plugin->getId())->toBe('blogr-artist');
    expect($plugin->getHomepage())->toBe('https://github.com/happytodev/blogr-artist');
});

test('getSettingsUrl returns null when extension is disabled', function () {
    $registry = app(ExtensionRegistry::class);
    $plugin = app(BlogrArtistPlugin::class);

    $registry->disable('blogr-artist');

    expect($plugin->getSettingsUrl())->toBeNull();

    $registry->enable('blogr-artist');
});

test('register does not add pages to panel when extension is disabled', function () {
    $registry = app(ExtensionRegistry::class);
    $plugin = app(BlogrArtistPlugin::class);

    $registry->disable('blogr-artist');

    $panel = Panel::make()->id('admin-test');

    $plugin->register($panel);

    expect($panel->getPages())->toBeEmpty();
    expect($panel->getResources())->toBeEmpty();

    $registry->enable('blogr-artist');
});

test('disabling the extension removes its pages from panel registration', function () {
    $registry = app(ExtensionRegistry::class);
    $plugin = app(BlogrArtistPlugin::class);

    // Register when enabled
    $panel = Panel::make()->id('admin-test-2');
    $plugin->register($panel);

    $pagesBefore = $panel->getPages();

    // Disable and register again
    $registry->disable('blogr-artist');
    $plugin->register($panel);

    expect($panel->getPages())->toEqual($pagesBefore);

    $registry->enable('blogr-artist');
});
