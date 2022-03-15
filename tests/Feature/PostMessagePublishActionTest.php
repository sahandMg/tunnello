<?php

namespace Tests\Feature;

use App\Http\Controllers\Api\Components\Channel\PostChannelCreateAction;
use App\Http\Controllers\Api\Components\Group\PostGroupCreateAction;
use App\Http\Controllers\Api\Components\Message\PostMessagePublishAction;
use App\Models\Group;
use App\Models\Message;
use App\Models\SocketChannel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class PostMessagePublishActionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        Artisan::call('migrate');
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_message_publish()
    {
        User::factory(3)->create();
        $sender = User::find(1);
        $recipient = User::find(2);
        $middleMan = User::find(3);
        $this->actingAs($sender);
        $m = [$middleMan->id, $recipient->id];
        $data = ['name' => 'test group33', 'members' => $m];
        PostGroupCreateAction::execute($data);
        PostChannelCreateAction::execute(['recipient_id' => $recipient->id]);
        PostMessagePublishAction::execute(['type' => 'solo', 'to' => $recipient->id, 'from' => $sender->id, 'msg' => 'Hey1 man']);
        PostChannelCreateAction::execute(['recipient_id' => $middleMan->id]);
        PostMessagePublishAction::execute(['type' => 'solo', 'to' => $middleMan->id, 'from' => $sender->id, 'msg' => 'Hey2 man']);
        PostMessagePublishAction::execute(['type' => 'group', 'to' => Group::first()->id, 'from' => $sender->id, 'msg' => 'Hey3 man']);
        $channels = SocketChannel::get();
        $this->assertEquals(7, $channels->count());
        $this->assertEquals(groupChannelId([$middleMan->id, $recipient->id, $sender->id]),$channels->where('type', 'group')->first()->name);
        $this->assertEquals(channelId($recipient->id, $sender->id),$channels->where('type', 'solo')->first()->name);
        $mesgs = Message::get();
        $this->assertEquals(3, $mesgs->count());
    }
}
