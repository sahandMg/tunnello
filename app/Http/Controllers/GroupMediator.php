<?php
/**
 * Created by PhpStorm.
 * User: Sahand
 * Date: 3/17/22
 * Time: 11:04 AM
 */

namespace App\Http\Controllers;


use App\Exceptions\ChannelNotFoundException;
use App\Exceptions\UserNotFoundException;
use Imanghafoori\Middlewarize\Middlewarable;

class GroupMediator
{
    use Middlewarable;

    public static function proxy($class_name, $args = null)
    {
        try{
            return $class_name::execute($args);
        }catch (\Exception $exception) {
            dd($exception->getMessage(), $exception->getFile(), $exception->getLine());
        }catch (UserNotFoundException $exception) {
            return $exception->render();
        }catch (ChannelNotFoundException $exception) {
            return $exception->render();
        }
    }
}