<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TmdbService
{
    protected array $config;

    public function __construct()
    {
        $this->config = config('tmdb');
    }

    public static function fetchMovies(string $searchKey): array
    {
        return (new self())->fetchMedia($searchKey, 'movie');
    }

    public static function fetchTVSeries(string $searchKey): array
    {
        return (new self())->fetchMedia($searchKey, 'tv');
    }

    public static function fetchMovieDetails(int $mediaId){
        return (new self())->fetchMediaDetails($mediaId,'movie');
    }
    public static function fetchTVSerieDetails(int $mediaId){
        return (new self())->fetchMediaDetails($mediaId,'tv');
    }

    public function fetchMovieGenres(){
        return (new self())->fetchMediaGenres('movie');
    }
    public function fetchTVSerieGenres(){
        return (new self())->fetchMediaGenres('tv');
    }

    protected static function fetchMedia(
        string $searchKey,
        string $mediaType,
        bool $include_adult = false,
        string $language = 'en-US',
        ?string $primary_release_year = null,
        int $page = 1,
        ?string $region = null,
        ?string $year = null,
        
    ): array {
        // $include_adult = $include_adult ? 'true' : 'false';
        $language = $language ?? 'en-US';
        // $page = max(1, $page); 

        // https://api.themoviedb.org/3/search/movie
        // https://api.themoviedb.org/3/search/tv

        ['api_key' => $apiKey, 'tmdb_url' => $tmdbUrl, 'tmdb_image_url' => $tmdbImageUrl, 'tmdb_image_resolution' => $tmdbImageResolution] = (new self())->config;
        $route = "{$tmdbUrl}/search/{$mediaType}?api_key={$apiKey}&query={$searchKey}";
        if ($include_adult) {
            $route .= "&include_adult={$include_adult}";
        }
        if ($language !== 'en-US') {
            $route .= "&language={$language}";
        }
        if ($page !== 1) {
            $route .= "&page={$page}";
        }

        
        $response = Http::get($route);
        $jsonData = $response->json();
        return $jsonData;
    }

    protected static function fetchMediaDetails(int $mediaId, string $mediaType,string $language='en-US',?string $append_to_response = null): array
    {
        $language = $language ?? 'en-US';
        // movies_details https://api.themoviedb.org/3/movie/{movie_id}
        // tvSeries_details https://api.themoviedb.org/3/tv/{series_id}

        ['api_key' => $apiKey, 'tmdb_url' => $tmdbUrl] = (new self())->config;
        $route = "{$tmdbUrl}/{$mediaType}/{$mediaId}?api_key={$apiKey}&language=en-US";

        if ($language !== 'en-US') {
            $route .= "&language={$language}";
        }

        $response = Http::get($route);
        $jsonData = $response->json();
        
        return $jsonData;
    }

    protected static function fetchMediaGenres(string $mediaType,string $language='en-US') {
        // https://api.themoviedb.org/3/genre/movie/list
        $language = $language ?? 'en-US';
        ['api_key' => $apiKey, 'tmdb_url' => $tmdbUrl] = (new self())->config;
        $route = "{$tmdbUrl}/genre/{$mediaType}/list?api_key={$apiKey}&language=en-US";

        $response = Http::get($route);
        $jsonData = $response->json();
        
        return $jsonData;
    }

    protected static function getBackdropPath(string $path,) {
        // https://image.tmdb.org/t/p/original/
        ['api_key' => $apiKey, 'tmdb_url' => $tmdbUrl , 'tmdb_image_url' => $tmdbBaseImageUrl] = (new self())->config;
        
    }
}
