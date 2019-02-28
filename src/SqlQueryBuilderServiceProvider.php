<?php

namespace SqlQueryBuilder;

use SqlQueryBuilder\SqlQueryBuilder;
use Illuminate\Support\ServiceProvider;

class SqlQueryBuilderServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    { }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SqlQueryBuilder::class, function () {
            return new SqlQueryBuilder();
        });
    }

    /**
     * Get the provided services.
     *
     * @return array
     */
    public function provides()
    {
        return ['SqlQueryBuilder'];
    }
}
