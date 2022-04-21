<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\Message;
use App\Models\SocketChannel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class MessageRoutesTest extends TestCase
{
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        Artisan::call('migrate');
        $data = [
            'fullname' => $this->faker->name,
            'username' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => '32132332321123',
            'password_confirmation' => '32132332321123',
            '_token' => csrf_token()
        ];
        $this->post('/api/register', $data);
    }

    /**
     * @test
     */
    public function publish_solo_route()
    {
        User::factory(4)->create();
        $sender = User::find(1);
        $recp = User::find(2);
        $middle = User::find(3);
        $this->actingAs($sender);
        $this->post(route('group.create'), ['name' => 'teddy', 'members' => [$sender->id, $recp->id, $middle->id]])
            ->assertOk();
        $this->post(route('channel.create'), ['type' => 'solo', 'recipient_id' => $recp->id])
            ->assertOk();
        $this->post(route('publish'), ['msg' => 'Salam', 'to' => $recp->id, 'from' => auth()->id(), 'type' => 'solo'])
            ->assertOk();
        $this->assertEquals(1, Message::get()->count());
        $this->assertEquals(1, Group::get()->count());
        $this->assertEquals(5, SocketChannel::get()->count());
    }

    /**
     * @test
     */
    public function publish_group_route()
    {
        User::factory(4)->create();
        $sender = User::find(1);
        $recp = User::find(2);
        $middle = User::find(3);
        $type = 'group';
        $this->post(route('group.create'), ['name' => 'teddy', 'members' => [$sender->id, $recp->id, $middle->id]])
            ->assertOk();
        $group = Group::find(1);
        $this->post(route('publish'), ['msg' => 'Salam', 'to' => $group->id, 'from' => auth()->id(), 'type' => $type])
            ->assertOk();
        $this->assertEquals(1, Message::get()->count());
        $this->assertEquals(1, Group::get()->count());
        $this->assertEquals(3, SocketChannel::get()->count());
    }

}