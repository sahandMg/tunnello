<?php

namespace App\Providers;

use App\Repositories\Facades\Response;
use App\Repositories\Respones\ApiResponse;
use App\Repositories\Respones\ViewResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Log::info(request()->has('phone'));
        $class = request('client') == 'web'? new ViewResponse() : new ApiResponse;
        Response::shouldProxyTo($class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        require_once app_path('helper.php');
    }
}
