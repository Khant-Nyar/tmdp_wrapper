<?php

declare(strict_types=1);

return [
    'tmdb_url'              => env('TMDB_URL', 'https://api.themoviedb.org').config('tmdb.tmdb_version'),
    'tmdb_version'          => env('TMDB_VERSION', '3'),
    'api_key'               => env('TMDB_APIKEY'),
    'tmdb_image_url'        => env('TMDB_IMAGE_URL', 'https://image.tmdb.org/t/p'),
    'tmdb_image_resolution' => env('TMDB_IMAGE_RESOLUTION', 'w780'),

    'images' => [
        'base_url'         => 'http://image.tmdb.org/t/p/',
        'secure_base_url'  => 'https://image.tmdb.org/t/p/',
        'backdrop_sizes'   => [
            'w300',
            'w780',
            'w1280',
            'original',
        ],
        'logo_sizes'       => [
            'w45',
            'w92',
            'w154',
            'w185',
            'w300',
            'w500',
            'original',
        ],
        'poster_sizes'     => [
            'w92',
            'w154',
            'w185',
            'w342',
            'w500',
            'w780',
            'original',
        ],
        'profile_sizes'    => [
            'w45',
            'w185',
            'h632',
            'original',
        ],
        'still_sizes'      => [
            'w92',
            'w185',
            'w300',
            'original',
        ],
    ],
];
