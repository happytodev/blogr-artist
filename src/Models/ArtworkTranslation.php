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
        'gallery',
        'price',
        'category_name',
        'tags',
        'is_available',
        'seo_title',
        'seo_description',
    ];

    protected $casts = [
        'gallery' => 'array',
        'tags' => 'array',
        'is_available' => 'boolean',
    ];

    public function artwork(): BelongsTo
    {
        return $this->belongsTo(Artwork::class, 'artwork_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            Category::class,
            'artwork_translation_category',
            'artwork_translation_id',
            'category_id'
        );
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(
            Tag::class,
            'artwork_translation_tag',
            'artwork_translation_id',
            'tag_id'
        );
    }
}
