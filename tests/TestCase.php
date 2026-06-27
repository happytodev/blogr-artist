<?php

namespace Happytodev\BlogrArtist\Tests;

use Happytodev\Blogr\BlogrServiceProvider;
use Happytodev\BlogrArtist\BlogrArtistServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Vite;
use Illuminate\Support\HtmlString;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Happytodev\\BlogrArtist\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );

        // Mock Vite to prevent manifest errors when rendering blogr views
        $this->mock(Vite::class, function (MockInterface $mock) {
            $mock->shouldReceive('__invoke')->andReturn(new HtmlString(''));
        });
    }

    protected function getPackageProviders($app)
    {
        return [
            BlogrServiceProvider::class,
            BlogrArtistServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        $app['config']->set('app.key', 'base64:/QGZSf6gflmQp4zukiY3ab0DnTFMOqLK1//pgpQhFzw=');
        $app['config']->set('session.driver', 'array');
        $app['config']->set('blogr.locales.enabled', false);
        $app['config']->set('blogr.route.prefix', 'blog');
        $app['config']->set('blogr.route.frontend.enabled', true);
        $app['config']->set('blogr.cms.enabled', true);
        $app['config']->set('blogr.cms.reserved_slugs', array_merge(
            $app['config']->get('blogr.cms.reserved_slugs', []),
            ['portfolio']
        ));
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function defineDatabaseMigrations()
    {
        // Load test migrations first (creates users table before Blogr's migrations alter it)
        $this->loadMigrationsFrom(dirname(__DIR__).'/tests/database/migrations');

        // Load Blogr package migrations
        $blogrMigrations = dirname(__DIR__).'/vendor/happytodev/blogr/database/migrations';
        if (is_dir($blogrMigrations)) {
            $this->loadMigrationsFrom($blogrMigrations);
        }

        // Then load plugin migrations
        $this->loadMigrationsFrom(dirname(__DIR__).'/database/migrations');
    }
}
