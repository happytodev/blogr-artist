<?php

namespace Happytodev\BlogrArtist\Http\Controllers;

use Happytodev\BlogrArtist\Models\Artwork;
use Illuminate\Routing\Controller;

class CommissionsController extends Controller
{
    public function index()
    {
        $commissions = Artwork::with('translations')
            ->published()
            ->forCommissions()
            ->ordered()
            ->get();

        return view('blogr-artist::portfolio.commissions', compact('commissions'));
    }
}
