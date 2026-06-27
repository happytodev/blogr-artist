<?php

namespace Happytodev\BlogrArtist\Filament\Resources;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Section;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteBulkAction;
use Happytodev\Blogr\Models\Category;
use Happytodev\Blogr\Models\Tag;
use Happytodev\BlogrArtist\Models\Artwork;
class ArtworkResource extends Resource
{
    protected static ?string $model = Artwork::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-photo';

    protected static string | \UnitEnum | null $navigationGroup = 'Portfolio';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Translations')
                    ->columnSpan(2)
                    ->schema([
                        Repeater::make('translations')
                            ->relationship('translations')
                            ->schema([
                                Select::make('locale')
                                    ->label('Locale')
                                    ->options(function () {
                                        $locales = config('blogr.locales.locales', ['en' => 'English']);
                                        $options = [];
                                        foreach ($locales as $code => $name) {
                                            $options[$code] = $name;
                                        }
                                        return $options;
                                    })
                                    ->required()
                                    ->columnSpan(1),

                                TextInput::make('title')
                                    ->label('Title')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(true, 1000)
                                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state)))
                                    ->columnSpan(2),

                                TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->columnSpan(1),

                                Textarea::make('description')
                                    ->label('Description')
                                    ->rows(4)
                                    ->columnSpan(2),

                                TextInput::make('price')
                                    ->label('Price (text)')
                                    ->placeholder('50€, 50-150€, Sur devis')
                                    ->maxLength(100)
                                    ->columnSpan(1),

                                Select::make('relatedTags')
                                    ->label('Tags')
                                    ->relationship('relatedTags', 'name')
                                    ->multiple()
                                    ->getOptionLabelFromRecordUsing(fn (Tag $tag) => $tag->getDefaultTranslation()?->name ?? $tag->name)
                                    ->columnSpan(2),

                                Toggle::make('is_available')
                                    ->label('Available for sale')
                                    ->default(true)
                                    ->columnSpan(1),

                                FileUpload::make('image')
                                    ->label('Main Image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('artworks')
                                    ->visibility('public')
                                    ->imageEditor()
                                    ->columnSpan(2),

                                FileUpload::make('gallery')
                                    ->label('Additional Images')
                                    ->multiple()
                                    ->reorderable()
                                    ->image()
                                    ->disk('public')
                                    ->directory('artworks/gallery')
                                    ->visibility('public')
                                    ->imageEditor()
                                    ->columnSpan(2),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->itemLabel(fn (array $state) => $state['title'] ?? __('New Translation'))
                            ->defaultItems(1)
                            ->minItems(1)
                            ->columnSpan(2),
                    ]),

                Section::make('Publication')
                    ->columnSpan(2)
                    ->schema([
                        Select::make('category_id')
                            ->label('Category')
                            ->relationship('category', 'name')
                            ->getOptionLabelFromRecordUsing(fn (Category $cat) => $cat->getDefaultTranslation()?->name ?? $cat->name)
                            ->columnSpan(1),

                        DateTimePicker::make('published_at')
                            ->label('Published At')
                            ->columnSpan(1),

                            
                        Toggle::make('is_featured')
                            ->label('Featured')
                            ->default(false)
                            ->inline()
                            ->columnSpan(1),

                        Toggle::make('is_published')
                            ->label('Published')
                            ->default(false)
                            ->inline()
                            ->columnSpan(1),
                            ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Image')
                    ->circular()
                    ->size(60)
                    ->disk('public')
                    ->getStateUsing(fn (Artwork $record): ?string => $record->getDefaultTranslation()?->image),

                TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->getStateUsing(fn (Artwork $record): ?string => $record->getDefaultTranslation()?->title),

                TextColumn::make('price')
                    ->label('Price')
                    ->getStateUsing(fn (Artwork $record): ?string => $record->getDefaultTranslation()?->price),

                TextColumn::make('category.name')
                    ->label('Category'),

                IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean(),

                IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),

                TextColumn::make('published_at')
                    ->label('Date')
                    ->date(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \Happytodev\BlogrArtist\Filament\Resources\ArtworkResource\Pages\ListArtworks::route('/'),
            'create' => \Happytodev\BlogrArtist\Filament\Resources\ArtworkResource\Pages\CreateArtwork::route('/create'),
            'edit' => \Happytodev\BlogrArtist\Filament\Resources\ArtworkResource\Pages\EditArtwork::route('/{record}/edit'),
        ];
    }
}
