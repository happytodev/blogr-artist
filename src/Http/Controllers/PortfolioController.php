<?php

namespace Happytodev\BlogrArtist\Http\Controllers;

use Happytodev\BlogrArtist\Models\Artwork;
use Illuminate\Routing\Controller;

class PortfolioController extends Controller
{
    public function index()
    {
        $show = config('blogr-artist.portfolio.show', 'featured');
        $maxImages = config('blogr-artist.portfolio.max_images', 6);

        $query = Artwork::with('translations')
            ->published()
            ->forPortfolio()
            ->ordered();

        if ($show === 'featured') {
            $query->featured();
        }

        $artworks = $query->take($maxImages)->get();

        return view('blogr-artist::portfolio.index', compact('artworks'));
    }

    public function show(string $slug)
    {
        $translation = \Happytodev\BlogrArtist\Models\ArtworkTranslation::with('artwork.translations')
            ->where('slug', $slug)
            ->firstOrFail();

        $artwork = $translation->artwork;

        if (! $artwork->is_published) {
            abort(404);
        }

        return view('blogr-artist::portfolio.show', compact('artwork', 'translation'));
    }
}
