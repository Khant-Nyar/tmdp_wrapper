
# tmdp_wrapper

The TMDB Wrapper package is a convenient Laravel wrapper for interacting with The Movie Database (TMDb) API. It provides easy-to-use methods to fetch movies, TV series, and their details.



## Installation

You can install the package via Composer:

```bash
  composer require khant-nyar/tmdb_wrapper
```
    
## Configuration

After installing the package, you need to publish the configuration file and set your TMDb API key:

```bash
php artisan vendor:publish --provider="KhantNyar\TmdbWrapper\TmdbWrapperServiceProvider"
```
Add your TMDb API key to the .env file:

```
TMDB_API_KEY=your_tmdb_api_key
```
    

## Usage/Examples

```php
public function fetchMovies(Request $request)
    {
        $searchKey = $request->input('search_key', 'Man of Steel');
        $movies = TmdbService::fetchMovies($searchKey);
        dd($movies);
        return response()->json(['movies' => $movies]);
    }

    public function fetchTVSeries(Request $request)
    {
        $searchKey = $request->input('search_key', 'Super Natural');
        $tvSeries = TmdbService::fetchTVSeries($searchKey);

        return response()->json(['tv_series' => $tvSeries]);
    }

    public function fetchMovieDetails(){
        $id = 49521;
        $details = TmdbService::fetchMovieDetails($id);
        return response()->json($details);
    }
    public function fetchTVSerieDetails(){
        $id = 157202;
        $details = TmdbService::fetchTVSerieDetails($id);
        return response()->json($details);
    }
```


## License

[MIT](https://github.com/Khant-Nyar/tmdp_wrapper/blob/main/LICENSE)


## Contributing

Contributions are always welcome!

See `contributing.md` for ways to get started.

Please adhere to this project's `code of conduct`.


## Support

For support, give a star and forge my repo .contact :khantnyar.dev@gmail.com


