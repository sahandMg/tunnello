<?php
/**
 * Created by PhpStorm.
 * User: Sahand
 * Date: 3/15/22
 * Time: 11:17 AM
 */

namespace App\Http\Controllers\Friend;


use App\Exceptions\UserNotFoundException;
use Imanghafoori\Middlewarize\Middlewarable;

class FriendMedietor
{
    use Middlewarable;

    public static function proxy($class_name, $args = null)
    {
        try{
            return $class_name::execute($args);
        }catch (UserNotFoundException $exception) {
            return $exception->render();
        }
    }
}