<?php

namespace Happytodev\BlogrArtist;

use Filament\Contracts\Plugin as FilamentPlugin;
use Filament\Panel;
use Happytodev\Blogr\Contracts\BlogrExtension;
use Happytodev\BlogrArtist\Filament\Resources\ArtworkResource;

class BlogrArtistPlugin implements BlogrExtension, FilamentPlugin
{
    public function getId(): string
    {
        return 'blogr-artist';
    }

    public function getName(): string
    {
        return 'Artist Portfolio';
    }

    public function getDescription(): string
    {
        return 'Artist portfolio with artwork management, portfolio pages, and commission showcase.';
    }

    public function getVersion(): string
    {
        return '1.0.0';
    }

    public function getAuthor(): string
    {
        return 'HappyToDev';
    }

    public function getHomepage(): ?string
    {
        return 'https://github.com/happytodev/blogr-artist';
    }

    public function getDependencies(): array
    {
        return ['blogr-core'];
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            ArtworkResource::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
