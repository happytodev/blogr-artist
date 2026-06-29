<?php

namespace Happytodev\BlogrArtist\Filament\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Facades\File;

class ArtistSettings extends Page
{
    use InteractsWithForms;

    protected static string|\UnitEnum|null $navigationGroup = 'Portfolio';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $title = 'Artist Settings';

    protected string $view = 'blogr-artist::filament.pages.artist-settings';

    protected static ?int $navigationSort = 5;

    public static function canAccess(): bool
    {
        return auth()->check();
    }

    // Portfolio settings
    public string $portfolio_url = 'portfolio';
    public string $portfolio_commissions_url = 'commissions';
    public bool $portfolio_lightbox_navigation = true;
    public int $portfolio_max_images = 6;

    // Commissions settings
    public int $commissions_autoplay_speed = 4000;

    public function mount(): void
    {
        $this->form->fill([
            'portfolio_url' => config('blogr-artist.portfolio.url', 'portfolio'),
            'portfolio_commissions_url' => config('blogr-artist.portfolio.commissions_url', 'commissions'),
            'portfolio_lightbox_navigation' => config('blogr-artist.portfolio.lightbox_navigation', true),
            'portfolio_max_images' => config('blogr-artist.portfolio.max_images', 6),
            'commissions_autoplay_speed' => config('blogr-artist.commissions.autoplay_speed', 4000),
        ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $configPath = __DIR__ . '/../../../config/blogr-artist.php';
        $content = File::get($configPath);

        $replacements = [
            "'url' => 'portfolio'" => "'url' => '{$data['portfolio_url']}'",
            "'commissions_url' => 'commissions'" => "'commissions_url' => '{$data['portfolio_commissions_url']}'",
            "'lightbox_navigation' => env('BLOGR_ARTIST_PORTFOLIO_LIGHTBOX_NAV', true)" => "'lightbox_navigation' => {$this->boolToString($data['portfolio_lightbox_navigation'])}",
            "'max_images' => 6" => "'max_images' => {$data['portfolio_max_images']}",
            "'autoplay_speed' => env('BLOGR_ARTIST_COMMISSIONS_AUTOPLAY_SPEED', 4000)" => "'autoplay_speed' => {$data['commissions_autoplay_speed']}",
        ];

        foreach ($replacements as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }

        File::put($configPath, $content);

        Notification::make()
            ->title('Settings saved')
            ->success()
            ->send();
    }

    protected function boolToString($value): string
    {
        return $value ? 'true' : 'false';
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make('Portfolio page')
                ->description('Configure how the /portfolio page displays artworks')
                ->schema([
                    TextInput::make('portfolio_url')
                        ->label('Portfolio page URL')
                        ->rule(function () {
                            $reserved = config('blogr.cms.reserved_slugs', []);

                            return function (string $value, \Closure $fail) use ($reserved): void {
                                $slug = trim($value, '/');

                                if (in_array($slug, $reserved, true)) {
                                    $fail("The URL \"{$slug}\" is reserved by the CMS.");
                                }

                                if (! preg_match('/^[a-z0-9\/_-]+$/', $slug)) {
                                    $fail('Only lowercase letters, numbers, hyphens, underscores, and slashes allowed.');
                                }
                            };
                        })
                        ->placeholder('portfolio')
                        ->helperText('The public URL path for the portfolio page. Use a simple slug like "portfolio" or "galerie".'),

                    Toggle::make('portfolio_lightbox_navigation')
                        ->label('Enable prev/next navigation in lightbox')
                        ->inline(),

                    TextInput::make('portfolio_max_images')
                        ->label('Max images to display')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(100),
                ])
                ->columns(2),

            Section::make('Commissions page')
                ->description('Configure how the /commissions page displays artworks')
                ->schema([
                    TextInput::make('portfolio_commissions_url')
                        ->label('Commissions page URL')
                        ->rule(function () {
                            $reserved = config('blogr.cms.reserved_slugs', []);

                            return function (string $value, \Closure $fail) use ($reserved): void {
                                $slug = trim($value, '/');

                                if (in_array($slug, $reserved, true)) {
                                    $fail("The URL \"{$slug}\" is reserved by the CMS.");
                                }

                                if (! preg_match('/^[a-z0-9\/_-]+$/', $slug)) {
                                    $fail('Only lowercase letters, numbers, hyphens, underscores, and slashes allowed.');
                                }
                            };
                        })
                        ->placeholder('commissions')
                        ->helperText('The public URL path for the commissions carousel page.'),

                    TextInput::make('commissions_autoplay_speed')
                        ->label('Autoplay speed (ms)')
                        ->numeric()
                        ->minValue(1000)
                        ->maxValue(15000)
                        ->suffix('ms')
                        ->helperText('Time between slides in milliseconds'),
                ])
                ->columns(2),
        ];
    }
}
