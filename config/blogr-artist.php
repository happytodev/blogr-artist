<?php

return [
    'route_prefix' => 'portfolio',

    'per_page' => 12,

    'thumbnail_height' => 250,

    /*
    |--------------------------------------------------------------------------
    | Portfolio page settings
    |--------------------------------------------------------------------------
    */
    'portfolio' => [
        'show' => env('BLOGR_ARTIST_PORTFOLIO_SHOW', 'featured'),
        'lightbox_navigation' => env('BLOGR_ARTIST_PORTFOLIO_LIGHTBOX_NAV', true),
        'image_height' => 400,
        'max_images' => 6,
    ],

    /*
    |--------------------------------------------------------------------------
    | Commissions page settings
    |--------------------------------------------------------------------------
    */
    'commissions' => [
        'show' => env('BLOGR_ARTIST_COMMISSIONS_SHOW', 'all'),
        'autoplay_speed' => env('BLOGR_ARTIST_COMMISSIONS_AUTOPLAY_SPEED', 4000),
        'image_height' => 500,
    ],
];
