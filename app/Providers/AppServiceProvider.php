<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Blade::directive('cirrus', function($expression) {
            /*return "<?php
            
                echo $expression;
            ?>";*/
            return "<?php echo $expression->format('m/d/Y H:i'); ?>";
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->bind(BrandRepositoryInterface::class, BrandRepositoryImpl::class);
        // $this->app->bind(PersonalRepositoryInterface::class, PersonalRepositoryImpl::class);
        
        // $this->app->singleton(ExcelTplInterface::class, ExcelTplImpl::class);
        $this->app->singleton('ItsMyHouse', function () {
            return new ItsMyHouse;
        });
    }
}

class ItsMyHouse 
{
    public function __get($name)
    {
        return $this;
    }

    public function __toString()
    {
        return '';
    }

    public function __call($func, $args)
    {
        return '';
    }
}