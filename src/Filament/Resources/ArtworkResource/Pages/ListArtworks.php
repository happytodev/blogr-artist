<?php

namespace Happytodev\BlogrArtist\Filament\Resources\ArtworkResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Happytodev\BlogrArtist\Filament\Resources\ArtworkResource;

class ListArtworks extends ListRecords
{
    protected static string $resource = ArtworkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
