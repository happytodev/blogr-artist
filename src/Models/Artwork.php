<?php

namespace Happytodev\BlogrArtist\Models;

use Happytodev\Blogr\Models\Category;
use Happytodev\Blogr\Models\Tag;
use Happytodev\BlogrArtist\Database\Factories\ArtworkFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Artwork extends Model implements Sortable
{
    use HasFactory, SortableTrait;

    protected $fillable = [
        'user_id',
        'category_id',
        'show_in_portfolio',
        'show_in_commissions',
        'is_published',
        'sort_order',
        'published_at',
    ];

    protected $casts = [
        'show_in_portfolio' => 'boolean',
        'show_in_commissions' => 'boolean',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(ArtworkTranslation::class, 'artwork_id');
    }

    public function translate(string $locale): ?ArtworkTranslation
    {
        return $this->translations->firstWhere('locale', $locale);
    }

    public function getDefaultTranslation(): ?ArtworkTranslation
    {
        $locale = app()->getLocale();
        return $this->translate($locale) ?? $this->translations->first();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)->whereNotNull('published_at');
    }

    public function scopeForPortfolio($query)
    {
        return $query->where('show_in_portfolio', true);
    }

    public function scopeForCommissions($query)
    {
        return $query->where('show_in_commissions', true);
    }

    protected static function newFactory(): ArtworkFactory
    {
        return ArtworkFactory::new();
    }
}
