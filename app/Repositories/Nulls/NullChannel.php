<?php
/**
 * Created by PhpStorm.
 * User: Sahand
 * Date: 3/15/22
 * Time: 9:48 PM
 */

namespace App\Repositories\Nulls;


use App\Models\SocketChannel;

class NullChannel extends SocketChannel
{
    public $wasRecentlyCreated = false;

}