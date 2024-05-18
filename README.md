# tmdp_wrapper
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
