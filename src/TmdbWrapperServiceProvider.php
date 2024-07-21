<?php

namespace KhantNyar\TmdbWrapper;

use Illuminate\Support\ServiceProvider;

class TmdbWrapperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergePackageEnv();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . "/../config/tmdb.php" => config_path("tmdb.php"),
        ], "tmdb-config");
    }

    protected function mergePackageEnv(): void
    {
        $packageEnvPath = __DIR__ . '/../env.tmdb';
        $appEnvPath = base_path('.env');

        if (file_exists($packageEnvPath)) {
            $packageEnv = file($packageEnvPath, FILE_IGNORE_NEW_LINES);

            $appEnv = file_exists($appEnvPath) ? file($appEnvPath, FILE_IGNORE_NEW_LINES) : [];
            $mergedEnv = array_merge($appEnv, $packageEnv);
            $mergedEnv = array_unique($mergedEnv);

            file_put_contents($appEnvPath, implode(PHP_EOL, $mergedEnv) . PHP_EOL);
        }
    }
}
