<?php

namespace Tests\Feature;

use App\Http\Controllers\Api\Components\Channel\GetChannelListAction;
use App\Http\Controllers\Api\Components\Channel\PostChannelCreateAction;
use App\Models\SocketChannel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class GetChannelListActionTest extends TestCase
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
    public function test_channel_list()
    {
        User::factory(3)->create();
        $sender = User::find(1);
        $recipient = User::find(2);
        $middleMan = User::find(3);
        $this->actingAs($sender);
        PostChannelCreateAction::execute(['recipient_id' => encode($recipient->id), 'type' => 'solo']);
        PostChannelCreateAction::execute(['recipient_id' => encode($middleMan->id), 'type' => 'solo']);
        PostChannelCreateAction::execute(['recipient_id' => encode($middleMan->id), 'type' => 'group']);
        $resp = GetChannelListAction::execute();
        $c = SocketChannel::query()->get();
        $this->assertEquals(4, $c->count());
        $this->assertEquals(2, count(json_decode($resp->getContent(), true)['solo']));

        $this->assertEquals(channelId(auth()->id(), $recipient->id) , $c->first()->name);
    }
}
