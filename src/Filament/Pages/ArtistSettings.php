<?php

namespace Happytodev\BlogrArtist\Filament\Pages;

use Filament\Forms\Components\Select;
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
    public string $portfolio_show = 'featured';
    public bool $portfolio_lightbox_navigation = true;
    public int $portfolio_image_height = 400;
    public int $portfolio_max_images = 6;

    // Commissions settings
    public string $commissions_show = 'all';
    public int $commissions_autoplay_speed = 4000;
    public int $commissions_image_height = 500;

    public function mount(): void
    {
        $this->form->fill([
            'portfolio_show' => config('blogr-artist.portfolio.show', 'featured'),
            'portfolio_lightbox_navigation' => config('blogr-artist.portfolio.lightbox_navigation', true),
            'portfolio_image_height' => config('blogr-artist.portfolio.image_height', 400),
            'portfolio_max_images' => config('blogr-artist.portfolio.max_images', 6),
            'commissions_show' => config('blogr-artist.commissions.show', 'all'),
            'commissions_autoplay_speed' => config('blogr-artist.commissions.autoplay_speed', 4000),
            'commissions_image_height' => config('blogr-artist.commissions.image_height', 500),
        ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $content = File::get(config_path('blogr-artist.php'));

        $replacements = [
            "'show' => env('BLOGR_ARTIST_PORTFOLIO_SHOW', 'featured')" => "'show' => '{$data['portfolio_show']}'",
            "'lightbox_navigation' => env('BLOGR_ARTIST_PORTFOLIO_LIGHTBOX_NAV', true)" => "'lightbox_navigation' => {$this->boolToString($data['portfolio_lightbox_navigation'])}",
            "'image_height' => 400" => "'image_height' => {$data['portfolio_image_height']}",
            "'max_images' => 6" => "'max_images' => {$data['portfolio_max_images']}",
            "'show' => env('BLOGR_ARTIST_COMMISSIONS_SHOW', 'all')" => "'show' => '{$data['commissions_show']}'",
            "'autoplay_speed' => env('BLOGR_ARTIST_COMMISSIONS_AUTOPLAY_SPEED', 4000)" => "'autoplay_speed' => {$data['commissions_autoplay_speed']}",
            "'image_height' => 500" => "'image_height' => {$data['commissions_image_height']}",
        ];

        foreach ($replacements as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }

        File::put(config_path('blogr-artist.php'), $content);

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
                    Select::make('portfolio_show')
                        ->label('Show artworks')
                        ->options([
                            'all' => 'All published artworks',
                            'featured' => 'Only featured artworks',
                        ])
                        ->helperText('Which artworks appear in the portfolio gallery'),

                    Toggle::make('portfolio_lightbox_navigation')
                        ->label('Enable prev/next navigation in lightbox')
                        ->inline(),

                    TextInput::make('portfolio_image_height')
                        ->label('Image height (px)')
                        ->numeric()
                        ->minValue(200)
                        ->maxValue(800)
                        ->suffix('px'),

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
                    Select::make('commissions_show')
                        ->label('Show artworks')
                        ->options([
                            'all' => 'All published artworks',
                            'featured' => 'Only featured artworks',
                        ])
                        ->helperText('Which artworks appear in the commissions carousel'),

                    TextInput::make('commissions_autoplay_speed')
                        ->label('Autoplay speed (ms)')
                        ->numeric()
                        ->minValue(1000)
                        ->maxValue(15000)
                        ->suffix('ms')
                        ->helperText('Time between slides in milliseconds'),

                    TextInput::make('commissions_image_height')
                        ->label('Image height (px)')
                        ->numeric()
                        ->minValue(200)
                        ->maxValue(800)
                        ->suffix('px'),
                ])
                ->columns(2),
        ];
    }
}
