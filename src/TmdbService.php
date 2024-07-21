<?php

namespace KhantNyar\TmdbWrapper;

use Illuminate\Support\Facades\Http;

class TmdbService
{
    protected array $config; //static

    protected array $defaultOptions = [
        'language' => 'en-US',
        'page'     => 1,
    ];

    public function __construct()
    {
        // static::config = config('tmdb');
        $this->config = config('tmdb');
    }

    public static function fetchLatestMovies($limit)
    {
        ['api_key' => $apiKey, 'tmdb_url' => $tmdbUrl, 'tmdb_image_url' => $tmdbImageUrl, 'tmdb_image_resolution' => $tmdbImageResolution] = (new self)->config;
        $response = Http::get("{$tmdbUrl}/movie/now_playing", [
            'api_key'  => $apiKey,
            'language' => 'en-US',
            'page'     => 1,
        ]);
        return collect($response->json()['results'])->take($limit)->toArray();
    }

    public static function fetchLatestSeries($limit)
    {
        ['api_key' => $apiKey, 'tmdb_url' => $tmdbUrl, 'tmdb_image_url' => $tmdbImageUrl, 'tmdb_image_resolution' => $tmdbImageResolution] = (new self)->config;
        $response = Http::get("{$tmdbUrl}/tv/on_the_air", [
            'api_key'  => $apiKey,
            'language' => 'en-US',
            'page'     => 1,
        ]);

        return collect($response->json()['results'])->take($limit)->toArray();
    }

    public static function fetchSeasonDetails(int $seriesId, int $seasonNumber)
    {
        ['api_key' => $apiKey, 'tmdb_url' => $tmdbUrl] = (new self)->config;

        $response = Http::get("{$tmdbUrl}/tv/{$seriesId}/season/{$seasonNumber}", [
            'api_key' => $apiKey,
        ]);

        return collect($response->json());
    }

    public static function fetchMovies(string $searchKey, ?string $append_to_response = null)
    {
        return (new self)->fetchMedia($searchKey, $append_to_response, 'movie');
    }

    public static function fetchTVSeries(string $searchKey, ?string $append_to_response = null)
    {
        return (new self)->fetchMedia($searchKey, $append_to_response, 'tv');
    }

    public static function fetchMovieDetails(
        int $mediaId,
        ?string $append_to_response = null
    ) {
        return (new self)->fetchMediaDetails($mediaId, 'movie', $append_to_response);
    }

    public static function fetchTVSerieDetails(int $mediaId, ?string $append_to_response = null)
    {
        return (new self)->fetchMediaDetails($mediaId, 'tv', $append_to_response);
    }

    public static function fetchMovieGenres()
    {
        return (new self)->fetchMediaGenres('movie');
    }

    public static function fetchTVSeriesGenres()
    {
        return (new self)->fetchMediaGenres('tv');
    }

    public function fetchTVSerieGenres()
    {
        return (new self)->fetchMediaGenres('tv');
    }

    public static function fetchMovieCredits($id)
    {
        return (new self)->fetchMediaCredits($id);
    }

    public static function fetchTVSeriesCredits($id)
    {
        return (new self)->fetchMediaCredits($id);
    }

    protected static function TmdbClient(string $route, array $options)
    {
        $mergedOptions = array_merge(self::$defaultOptions, $options);

        try {
            $response = Http::get($route, $mergedOptions);

            if ($response->successful()) {
                return $response;
            } else {
                throw new \Exception('TMDB API request failed: ' . $response->status());
            }
        } catch (\Exception $e) {
            return;
        }
    }

    protected static function fetchMedia(
        string $searchKey,
        ?string $append_to_response,
        string $mediaType,
        bool $include_adult = false,
        string $language = 'en-US',
        ?string $primary_release_year = null,
        int $page = 1,
        ?string $region = null,
        ?string $year = null,
    ) {
        // $include_adult = $include_adult ? 'true' : 'false';
        $language ??= 'en-US';
        // $page = max(1, $page);

        // https://api.themoviedb.org/3/search/movie
        // https://api.themoviedb.org/3/search/tv

        ['api_key' => $apiKey, 'tmdb_url' => $tmdbUrl, 'tmdb_image_url' => $tmdbImageUrl, 'tmdb_image_resolution' => $tmdbImageResolution] = (new self)->config;

        return Http::get("{$tmdbUrl}/search/{$mediaType}", [
            'api_key'            => $apiKey,
            'query'              => $searchKey,
            'language'           => $language,
            'page'               => $page,
            'include_adult'      => $include_adult,
            'append_to_response' => $append_to_response,
        ])->collect('results');
        // return Http::get($route)->collect('results');
    }

    protected static function fetchMediaDetails(int $mediaId, string $mediaType, ?string $append_to_response = null, string $language = 'en-US')
    {
        ['api_key' => $apiKey, 'tmdb_url' => $tmdbUrl] = (new self)->config;
        $route = "{$tmdbUrl}/{$mediaType}/{$mediaId}?api_key={$apiKey}&language=en-US";

        return Http::get("{$tmdbUrl}/{$mediaType}/{$mediaId}", [
            'api_key'            => $apiKey,
            'language'           => $language,
            'append_to_response' => $append_to_response,
        ])->collect();
    }

    // https://api.themoviedb.org/3/credit/{credit_id}

    protected static function fetchMediaGenres(string $mediaType, string $language = 'en-US')
    {
        ['api_key' => $apiKey, 'tmdb_url' => $tmdbUrl] = (new self)->config;

        $response = Http::get("{$tmdbUrl}/genre/{$mediaType}/list", [
            'api_key'  => $apiKey,
            'language' => $language,
        ])->collect('genres');

        return $response;
    }

    protected static function fetchMediaCredits($id, string $mediaType = 'movie', string $credits_type = 'cast', string $language = 'en-US')
    {
        ['api_key' => $apiKey, 'tmdb_url' => $tmdbUrl] = (new self)->config;

        return Http::get("{$tmdbUrl}/{$mediaType}/{$id}/credits", [
            'api_key'  => $apiKey,
            'language' => $language,
        ])->collect($credits_type);
    }

    protected static function getBackdropPath(string $path): void
    {
        // https://image.tmdb.org/t/p/original/
        ['api_key' => $apiKey, 'tmdb_url' => $tmdbUrl, 'tmdb_image_url' => $tmdbBaseImageUrl] = (new self)->config;
    }
}
