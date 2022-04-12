<?php
/**
 * Created by PhpStorm.
 * User: Sahand
 * Date: 4/10/22
 * Time: 4:42 PM
 */

namespace App\Services;


class ResponseStates
{
    const INVALID_CREDS = 'Invalid Credentials';
    const REGISTER_SUCCESS = 'User Registered';
    const LOGIN_SUCCESS = 'Logged In';
    const REGISTRATION_ERROR = 'Something went wrong during registration';
    const OK = 'OK';
    const LOG_OUT = 'Logged Out!';
    const USER_NOT_FOUND = 'User Not Found !';
    const SELF_FRIEND_ADD = 'Add Your Self As A Friend ?!';
    const DUPLICATE_FRIEND = 'This Guy Has Already Been Added !';
}