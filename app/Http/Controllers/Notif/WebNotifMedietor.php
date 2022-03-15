<?php
/**
 * Created by PhpStorm.
 * User: Sahand
 * Date: 3/15/22
 * Time: 11:17 AM
 */

namespace App\Http\Controllers\Notif;


use Imanghafoori\Middlewarize\Middlewarable;

class WebNotifMedietor
{
    use Middlewarable;

    public static function proxy($class_name, $args = null)
    {
        try{
            return $class_name::execute($args);
        }catch (\Exception $exception) {
            dd($exception->getMessage(), $exception->getFile(), $exception->getLine());
        }
    }
}