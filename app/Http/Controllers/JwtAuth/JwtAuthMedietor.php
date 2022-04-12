<?php
/**
 * Created by PhpStorm.
 * User: Sahand
 * Date: 3/15/22
 * Time: 11:17 AM
 */

namespace App\Http\Controllers\JwtAuth;


use App\Exceptions\AuthRegisterException;
use App\Exceptions\GenericException;
use App\Exceptions\InvalidCredentialException;
use App\Exceptions\SelfFriendException;
use App\Exceptions\UserNotFoundException;
use Imanghafoori\Middlewarize\Middlewarable;

class JwtAuthMedietor
{
    use Middlewarable;

    public static function proxy($class_name, $args = null)
    {
        try{
            return $class_name::execute($args);
        }catch (AuthRegisterException $exception) {
            return $exception->render();
        }catch (GenericException $exception)
        {
            $exception->render();
        }
        catch (InvalidCredentialException $exception) {
            $exception->render();
        }
    }
}