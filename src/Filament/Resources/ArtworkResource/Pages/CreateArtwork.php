<?php

namespace Happytodev\BlogrArtist\Filament\Resources\ArtworkResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Happytodev\BlogrArtist\Filament\Resources\ArtworkResource;

class CreateArtwork extends CreateRecord
{
    protected static string $resource = ArtworkResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        \Illuminate\Support\Facades\Log::info('CreateArtwork mutateFormDataBeforeCreate', [
            'data_keys' => array_keys($data),
            'has_translations' => isset($data['translations']),
            'translations_count' => is_array($data['translations'] ?? null) ? count($data['translations']) : 0,
            'first_translation_keys' => is_array($data['translations'][0] ?? null) ? array_keys($data['translations'][0]) : [],
        ]);

        if (isset($data['translations']) && is_array($data['translations'])) {
            $first = reset($data['translations']);
            if (isset($first['category_id'])) {
                $data['category_id'] = $first['category_id'];
            }
            foreach ($data['translations'] as $key => $t) {
                unset($data['translations'][$key]['category_id']);
            }
        }

        return $data;
    }
}
