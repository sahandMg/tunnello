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

class MixTest extends TestCase
{
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        Artisan::call('migrate');
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => '32132332321123',
            'password_confirmation' => '32132332321123',
            '_token' => csrf_token()
        ];
        $this->post('/register', $data);
    }

    /**
     * @test
     */
    public function group_publish_check()
    {
        User::factory(6)->create();
        $r0 = User::find(1);
        $r1 = User::find(2);
        $r2 = User::find(3);
        $r3 = User::find(4);
        $r4 = User::find(5);
        $r5 = User::find(6);
        $type = 'group';
        $this->post(route('group.create'), ['name' => 'G1', 'members' => [$r0->id, $r1->id, $r2->id]]) // 3 channels
            ->assertOk();
        $this->post(route('group.create'), ['name' => 'G2', 'members' => [$r3->id, $r4->id, $r5->id]]) // 4 channels
            ->assertOk();
        $this->post(route('group.create'), ['name' => 'G3', 'members' => [$r0->id, $r4->id, $r5->id]]) // 3 channels
        ->assertOk();
        $group = Group::find(1);
        $group2 = Group::find(2);
        $this->post(route('publish'), ['msg' => 'Salam', 'to' => $group->id, 'from' => auth()->id(), 'type' => $type])
            ->assertOk();
        $this->post(route('publish'), ['msg' => 'Salam', 'to' => $group2->id, 'from' => auth()->id(), 'type' => $type])
            ->assertOk();
        $this->assertEquals(2, Message::get()->count());
        $this->assertEquals(3, Group::get()->count());
        $this->assertEquals(10, SocketChannel::get()->count());
        $this->assertEquals(1, Message::find(1)->group_id);
        $this->assertEquals(2, Message::find(2)->group_id);
    }
}