<?php

namespace Happytodev\BlogrArtist;

use Filament\Contracts\Plugin as FilamentPlugin;
use Filament\Panel;
use Happytodev\Blogr\Contracts\BlogrExtension;
use Happytodev\Blogr\Services\LinkTypeRegistry;
use Happytodev\BlogrArtist\Filament\Pages\ArtistSettings;
use Happytodev\BlogrArtist\Filament\Resources\ArtworkResource;
use Illuminate\Support\Facades\DB;

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

    public function getSettingsUrl(): ?string
    {
        try {
            $disabled = DB::table('blogr_extension_states')
                ->where('extension_id', 'blogr-artist')
                ->whereNotNull('disabled_at')
                ->exists();
        } catch (\Throwable) {
            $disabled = false;
        }

        if ($disabled) {
            return null;
        }

        try {
            return ArtistSettings::getUrl();
        } catch (\Throwable) {
            return null;
        }
    }

    public function registerExtension(\Happytodev\Blogr\Services\ExtensionRegistry $registry): void {}

    public function registerLinkTypes(LinkTypeRegistry $registry): void
    {
        $registry->register(
            'artist_portfolio',
            'Portfolio',
            fn (): string => route('artist.portfolio.index')
        );

        $registry->register(
            'artist_commissions',
            'Commissions',
            fn (): string => route('artist.commissions.index')
        );
    }

    public function register(Panel $panel): void
    {
        try {
            $disabled = DB::table('blogr_extension_states')
                ->where('extension_id', 'blogr-artist')
                ->whereNotNull('disabled_at')
                ->exists();
        } catch (\Throwable) {
            $disabled = false;
        }

        if ($disabled) {
            return;
        }

        $panel->resources([
            ArtworkResource::class,
        ])->pages([
            ArtistSettings::class,
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
