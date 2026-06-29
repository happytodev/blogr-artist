<?php

namespace Happytodev\BlogrArtist\Models;

use Happytodev\Blogr\Models\Category;
use Happytodev\Blogr\Models\Tag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ArtworkTranslation extends Model
{
    protected $fillable = [
        'artwork_id',
        'locale',
        'title',
        'slug',
        'description',
        'image',
        'cropped_image',
        'gallery',
        'price',
        'category_name',
        'tags',
        'status',
        'seo_title',
        'seo_description',
    ];

    protected $casts = [
        'gallery' => 'array',
        'tags' => 'array',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $translation): void {
            if ($translation->image && ! $translation->cropped_image) {
                $translation->cropped_image = $translation->image;
            }
        });
    }

    public function artwork(): BelongsTo
    {
        return $this->belongsTo(Artwork::class, 'artwork_id');
    }

    public function relatedTags(): BelongsToMany
    {
        return $this->belongsToMany(
            Tag::class,
            'artwork_translation_tag',
            'artwork_translation_id',
            'tag_id'
        );
    }
}
