<?php

namespace Happytodev\BlogrArtist\Filament\Resources\ArtworkResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Happytodev\BlogrArtist\Filament\Resources\ArtworkResource;

class CreateArtwork extends CreateRecord
{
    protected static string $resource = ArtworkResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (isset($data['translations']) && is_array($data['translations'])) {
            $first = reset($data['translations']);
            if (isset($first['category_id'])) {
                $data['category_id'] = $first['category_id'];
            }
            array_walk($data['translations'], fn (&$t) => unset($t['category_id']));
        }

        return $data;
    }
}
