<?php

namespace Happytodev\BlogrArtist\Http\Controllers;

use Happytodev\BlogrArtist\Models\Artwork;
use Illuminate\Routing\Controller;

class CommissionsController extends Controller
{
    public function index()
    {
        $show = config('blogr-artist.commissions.show', 'all');

        $query = Artwork::with('translations')
            ->published()
            ->forCommissions();

        if ($show === 'featured') {
            $query->featured();
        }

        $commissions = $query->ordered()->get();

        return view('blogr-artist::portfolio.commissions', compact('commissions'));
    }
}
