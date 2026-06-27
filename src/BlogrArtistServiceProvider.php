<?php

namespace Happytodev\BlogrArtist;

use Filament\PanelRegistry;
use Happytodev\Blogr\Services\ExtensionRegistry;
use Happytodev\BlogrArtist\Filament\Resources\ArtworkResource\Pages\CreateArtwork;
use Happytodev\BlogrArtist\Filament\Resources\ArtworkResource\Pages\EditArtwork;
use Happytodev\BlogrArtist\Filament\Resources\ArtworkResource\Pages\ListArtworks;
use Happytodev\BlogrArtist\Http\Controllers\PortfolioController;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class BlogrArtistServiceProvider extends PackageServiceProvider
{
    public static string $name = 'blogr-artist';

    public static string $viewNamespace = 'blogr-artist';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasConfigFile()
            ->hasViews(static::$viewNamespace);
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(BlogrArtistPlugin::class, fn () => new BlogrArtistPlugin);

        $this->app->afterResolving(PanelRegistry::class, function (PanelRegistry $registry): void {
            $panel = $registry->get('admin');

            if (! $panel) {
                return;
            }

            $panel->plugin($this->app->make(BlogrArtistPlugin::class));
        });
    }

    public function packageBooted(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->registerExtensions();
        $this->registerLivewireComponents();

        if ($this->isExtensionEnabled()) {
            $this->registerRoutes();
        }
    }

    protected function registerLivewireComponents(): void
    {
        $pages = [
            ListArtworks::class,
            CreateArtwork::class,
            EditArtwork::class,
        ];

        foreach ($pages as $page) {
            $name = app(\Livewire\Mechanisms\ComponentRegistry::class)->getName($page);
            Livewire::component($name, $page);
        }
    }

    protected function registerExtensions(): void
    {
        if ($this->app->has(ExtensionRegistry::class)) {
            $registry = $this->app->make(ExtensionRegistry::class);
            $registry->register($this->app->make(BlogrArtistPlugin::class));
        }
    }

    protected function isExtensionEnabled(): bool
    {
        if (! $this->app->has(ExtensionRegistry::class)) {
            return true;
        }

        return $this->app->make(ExtensionRegistry::class)->isEnabled('blogr-artist');
    }

    protected function registerRoutes(): void
    {
        $router = $this->app['router'];
        $prefix = config('blogr-artist.route_prefix', 'portfolio');

        $router->group([
            'middleware' => config('blogr.route.middleware', ['web']),
        ], function () use ($router, $prefix) {
            $router->get($prefix, [PortfolioController::class, 'index'])
                ->name('artist.portfolio.index');

            $router->get($prefix . '/{slug}', [PortfolioController::class, 'show'])
                ->name('artist.portfolio.show');
        });
    }
}
