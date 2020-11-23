<?php

namespace Tests\Feature\Services;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Services\UserService;
use App\Models\User;
use Faker\Generator as Faker;

class UserServiceTest extends TestCase
{
    use WithFaker;

    private $userService;
    private $user;

    public function setUp(): void
    {
        $this->userService = app(UserService::class);
        $this->user = app(User::class);
        parent::setUp();
    }
    
    /** @test */
    public function check_if_create()
    {
        $data = [
            'name' => 'Teste',
            'email' => $this->faker->unique()->email,
            'password' => '123456'
        ];

        $user = $this->userService->save($data);

        $this->assertInstanceOf(User::class, $user); 
        $this->assertEquals($data['name'], $user->name);
        $this->assertEquals($data['email'], $user->email);
    }    
}
