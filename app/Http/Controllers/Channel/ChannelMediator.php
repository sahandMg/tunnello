<?php
/**
 * Created by PhpStorm.
 * User: Sahand
 * Date: 3/17/22
 * Time: 11:04 AM
 */

namespace App\Http\Controllers\Channel;


use App\Exceptions\UserNotFoundException;
use Imanghafoori\Middlewarize\Middlewarable;

class ChannelMediator
{
    use Middlewarable;

    public static function proxy($class_name, $args = null)
    {
        try{
            return $class_name::execute($args);
        }catch (\Exception $exception) {
//            $exception->render();
return($exception->getMessage() .' '. $exception->getFile() .' '.$exception->getLine());
        }catch (UserNotFoundException $exception) {
            return $exception->render();
        }
    }
}
