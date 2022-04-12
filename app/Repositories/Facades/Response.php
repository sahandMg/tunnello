<?php
/**
 * Created by PhpStorm.
 * User: Sahand
 * Date: 3/17/22
 * Time: 8:58 PM
 */

namespace App\Repositories\Facades;


use Illuminate\Support\Facades\Facade;

class Response extends Facade
{

    public static function getFacadeAccessor()
    {
        return 'app.response';
    }

    public static function shouldProxyTo($class)
    {
        app()->singleton(self::getFacadeAccessor(), function() use($class){
            return $class;
        });
    }
}