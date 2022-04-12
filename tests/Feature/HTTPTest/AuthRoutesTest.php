<?php

namespace Tests\Feature\HTTPTest;

use App\Models\User;

use App\Repositories\DB\AuthDB;
use App\Services\DataFormatter;
use Illuminate\Foundation\Testing\Concerns\InteractsWithSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class AuthRoutesTest extends TestCase
{
    use WithFaker, InteractsWithSession;
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
    public function register_test_route()
    {
        $data = [
            'fullname' => $this->faker->name,
            'username' => $this->faker->name,
            'phone' => randomPhone(),
            'password' => '32132332321123',
            'password_confirmation' => '32132332321123',
        ];
        $response = $this->post('/api/register', $data);
        $response->assertStatus(200);
        $this->assertEquals(1, User::get()->count());
        $response->assertJsonFragment(['status' => 200]);
        $response->assertOk();
    }

    /**
     * @test
     */

    public function register_test_with_error()
    {
        $data = [
            'fullname' => $this->faker->name,
//            'username' => $this->faker->name,
            'phone' => randomPhone(),
            'password' => '32132332321123',
            'password_confirmation' => '32132332321123',
            '_token' => csrf_token()
        ];
        $response = $this->post('/api/register', $data);
        $response->assertStatus(400);
        $this->assertEquals(0, User::get()->count());
        $response->assertJsonFragment(['status' => 400]);
//        $response = $this->post('/register', $data)->assertSessionHasErrors();
    }

    /**
     * @test
     */

    public function login_route_test()
    {
        $reg_data = [
            'fullname' => $this->faker->name,
            'username' => $this->faker->name,
            'phone' => randomPhone(),
            'password' => '32132332321123',
            'password_confirmation' => '32132332321123',
        ];
        $login_data = [
//            'username' => $reg_data['username'],
            'phone' => $reg_data['phone'],
            'password' => '32132332321123',
        ];
        $reg_resp = $this->post('/api/register', $reg_data);
        $token = json_decode($reg_resp->content(), true)['token'];
        $response = $this->post('/api/login', $login_data);
        $response->assertOk();
        $this->assertTrue(auth()->check());
        $this->assertEquals(1, User::get()->count());

    }

    /**
     * @test
     */

    public function logout_route_test()
    {
        $reg_data = [
            'fullname' => $this->faker->name,
            'username' => $this->faker->name,
            'phone' => randomPhone(),
            'password' => '32132332321123',
            'password_confirmation' => '32132332321123',
        ];
        $login_data = [
//            'username' => $reg_data['username'],
            'phone' => $reg_data['phone'],
            'password' => '32132332321123',
        ];
        $reg_resp = $this->post('/api/register', $reg_data);
        $token = json_decode($reg_resp->content(), true)['token'];
        $this->post('/api/login', $login_data);
        $this->assertTrue(auth()->check());
        $this->assertEquals(1, User::get()->count());
        $response = $this->post('/api/logout');
        $response->assertOk();
        $this->assertTrue(!auth()->check());

    }
}
