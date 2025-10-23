<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Settings;

class myHelperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //share to spesific blade(page)
        // View::composer(['welcome','auth.login','backend.layouts.sidebar','backend.layouts.app'],function($view){
        //     $setting= Settings::where('id',1)->first();
        //     $view->with('setting',$setting);
        // });

        //share to all blade(page)
        View::composer('*',function($view){
            $setting= Settings::where('id',1)->first();
            $view->with('setting',$setting);
        });
    }
}
