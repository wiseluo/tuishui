<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class ValidatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('image_str', function ($attribute, $value, $parameters) {
            $str_arr = explode('|', $value);
            $image_arr = array_filter($str_arr, function ($v) use ($parameters) {
                $extens = strtolower(pathinfo($v, PATHINFO_EXTENSION));
                return !in_array($extens, $parameters);
            });
            if(empty($image_arr)){
                return true;
            }else{
                return false;
            }
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
