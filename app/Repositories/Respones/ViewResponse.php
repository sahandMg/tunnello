<?php
/**
 * Created by PhpStorm.
 * User: Sahand
 * Date: 3/17/22
 * Time: 9:01 PM
 */

namespace App\Repositories\Respones;


class ViewResponse
{
    public function home($resp)
    {
        return view('chat-back', $resp);
    }

    public function chats($data)
    {
        return view('chat', $data);
    }
}