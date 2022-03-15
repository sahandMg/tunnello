<?php

namespace Tests\Feature;

use App\Http\Controllers\Api\Components\Channel\PostChannelCreateAction;
use App\Http\Controllers\Api\Components\Group\PostGroupCreateAction;
use App\Http\Controllers\Api\Components\Home\GetHomeDataAction;
use App\Http\Controllers\Api\Components\Message\PostMessagePublishAction;
use App\Models\Group;
use App\Models\Message;
use App\Models\User;
use App\Repositories\DB\GroupDB;
use App\Repositories\DB\MessageDB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class GetHomeDataActionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        Artisan::call('migrate');
    }

    public function test_home_data()
    {
        User::factory(3)->create();
        $sender = User::find(1);
        $rep = User::find(2);
        $mid = User::find(3);
        $this->actingAs($sender);
        PostGroupCreateAction::execute(['name' => 'teddy', 'members' => [$rep->id, $mid->id]]);
        PostChannelCreateAction::execute(['recipient_id' => $rep->id]);
        PostMessagePublishAction::execute(['type' => 'solo', 'to' => $rep->id, 'from' => $sender->id, 'msg' => 'Hey1 man']);
        PostChannelCreateAction::execute(['recipient_id' => $mid->id]);
        PostMessagePublishAction::execute(['type' => 'solo', 'to' => $mid->id, 'from' => $sender->id, 'msg' => 'Hey2 man']);
        PostMessagePublishAction::execute(['type' => 'group', 'to' => Group::first()->id, 'from' => $sender->id, 'msg' => 'Hey3 man']);
        $val = GetHomeDataAction::execute();
//        'auth_user_messages', 'users', 'user_solo_channels', 'user_groups', 'user_group_channels'
        $this->assertEquals(3, $val['auth_user_messages']->count());
        $this->assertEquals(2, $val['users']->count());
        $this->assertEquals(2, $val['user_solo_channels']->count());
        $this->assertEquals(1, $val['user_groups']->count());
        $this->assertEquals(1, $val['user_group_channels']->count());
    }
}
