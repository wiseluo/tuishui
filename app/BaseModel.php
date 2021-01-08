<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/3
 * Time: 13:47
 */

namespace App;

use App\Http\Traits\Search;
use App\Observers\ModelObserver;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;

class BaseModel extends Authenticatable
{
    use Notifiable,SoftDeletes,SearchableTrait,Search;

    public static function boot()
    {
        parent::boot();

        static::observe(new ModelObserver());
    }

    public function __get($key)
    {
        // if($_SERVER['REQUEST_URI'] === '/rebate/refund') {
        //     return null !== parent::__get($key) ? parent::__get($key) : resolve('ItsMyHouse');
        // }
        
        return parent::__get($key) ;
    }

}

