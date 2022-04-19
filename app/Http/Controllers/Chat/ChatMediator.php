<?php
/**
 * Created by PhpStorm.
 * User: Sahand
 * Date: 3/14/22
 * Time: 10:27 PM
 */

namespace App\Http\Controllers\Chat;


use App\Exceptions\PublishMessageBadRequestException;
use App\Exceptions\UserNotFoundException;
use Imanghafoori\Middlewarize\Middlewarable;

class ChatMediator
{
    use Middlewarable;

    public static function proxy($class_name, $args = null)
    {
        try{
            return $class_name::execute($args);
        }catch (\Exception $exception) {
            return($exception->getMessage() .' '. $exception->getFile() .' '.$exception->getLine());
        }catch (UserNotFoundException $exception) {
            return $exception->render();
        }
    }
}
