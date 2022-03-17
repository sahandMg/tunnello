<?php

namespace Tests\Feature;

use App\Exceptions\ChannelNotFoundException;
use App\Http\Controllers\Api\Components\Group\PostGroupCreateAction;
use App\Models\Group;
use App\Models\SocketChannel;
use App\Models\User;
use App\Repositories\DB\GroupDB;
use App\Services\Channel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class PostGroupCreateActionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        Artisan::call('migrate');
    }

    /**
     * A basic feature test example.
     * @test
     * @return void
     */
    public function test_group_component()
    {
        User::factory(3)->create();
        $sender = User::find(1);
        $recipient = User::find(2);
        $middleMan = User::find(3);
        $this->actingAs($sender);
        $m = [$middleMan->id, $recipient->id];
        $data = ['name' => 'test group', 'members' => $m];
        $group = PostGroupCreateAction::execute($data);
        $group_channel = Channel::createChannelForGroupMembers([1,2,3]);
        GroupDB::updateGroupChannel($group, $group_channel);
       $this->assertEquals(1, Group::get()->count());
       $this->assertEquals('test group', Group::find(1)->name);
       $this->assertEquals(3, SocketChannel::get()->count());
       $this->assertEquals($group_channel->name, SocketChannel::first()->name);
       $this->assertEquals(3, Group::first()->users->count());
    }
}
