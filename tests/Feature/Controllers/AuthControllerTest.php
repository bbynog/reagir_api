<?php

namespace Tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use phpDocumentor\Reflection\Types\Null_;

class AuthControllerTest extends TestCase
{
    use WithFaker;    

    /** @test */
    public function check_if_registration_is_successful()
    {
        $response = $this->postJson('/api/register', [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('123456'),
        ]);        
        
        $response->assertJson([            
            'token_type' => 'Bearer'
        ]);
        $response->assertStatus(200);   
    }

    /** @test */
    public function check_if_registration_is_unsuccessful()
    {
        $response = $this->postJson('/api/register', [
            'name' => $this->faker->name,            
            'password' => bcrypt('123456'),
        ]);         
        
        $response->assertJson([
            "message" => "The given data was invalid.",
            "errors" => [
                "email" => [
                    "The email field is required."
                ]
            ]
        ]);
        
        $response->assertStatus(422);   
    }

    /** @test */
    public function check_if_login_is_succesful()
    {
        $response = $this->postJson('api/login', [
            'email' => 'teste@gmail.com',
            'password' => 'teste'
        ]);

        $response->assertJson([
            'token_type' => 'Bearer'
        ]);
        $response->assertStatus(200);
    }

    /** @test */
    public function check_if_login_validation_is_unsuccessful()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'bibi',            
            'password' => '',
        ]);       
        
        $response->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
              'email' => [
                'The email must be a valid email address.'
              ],
              'password' => [
                'The password field is required.'
              ]
            ]
          ]);
        
        $response->assertStatus(422);   
    }

    /** @test */
    public function check_if_login_is_unsuccessful()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'bibi@xcxcx.com',            
            'password' => '123456',
        ]);       
        
        $response->assertJson([            
            'message' => 'Unauthorized'
        ]);
        
        $response->assertStatus(401);   
    }
}
