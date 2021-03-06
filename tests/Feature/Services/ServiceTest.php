<?php

namespace Tests\Feature\Services;

use App\Models\User;
use App\Repositories\Enum\AppEnum;
use App\Services\DataFormatter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ServiceTest extends TestCase
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
    public function collection_shaper_test()
    {
        $arr = ['name' => 'sahand', 'number' => 123, 'nc' => '00223331', 'age' => 21];
        $collection = collect($arr);
        $data = DataFormatter::collectionShaper($collection, ['name','age']);
        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('age', $data);
        $this->assertArrayNotHasKey('number', $data);
        $data = DataFormatter::collectionShaper($collection, ['name','age'], AppEnum::SHAPE_Except);
        $this->assertArrayNotHasKey('name', $data);
        $this->assertArrayNotHasKey('age', $data);
        $this->assertArrayHasKey('number', $data);
    }

    /**
     * A basic feature test example.
     * @test
     * @return void
     */
    public function model_shaper_test()
    {
        User::factory(1)->create();
        $user = User::find(1);
        $data = DataFormatter::modelShaper($user, ['username', 'phone']);
        $this->assertArrayNotHasKey('fullname', $data);
        $this->assertArrayNotHasKey('password', $data);
        $this->assertArrayNotHasKey('id', $data);
        $this->assertArrayHasKey('username', $data);
        $this->assertArrayHasKey('phone', $data);
        $data2 = DataFormatter::modelShaper($user, ['username', 'phone'], AppEnum::SHAPE_Except);
        $this->assertArrayHasKey('fullname', $data2);
        $this->assertArrayHasKey('password', $data2);
        $this->assertArrayHasKey('id', $data2);
        $this->assertArrayNotHasKey('username', $data2);
        $this->assertArrayNotHasKey('phone', $data2);
    }
}
