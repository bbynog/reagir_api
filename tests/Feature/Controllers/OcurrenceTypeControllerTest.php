<?php

namespace Tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Passport\Passport;

class OcurrenceTypeControllerTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function check_if_index_is_showing_list()
    {
        #Creating and acting as User
        $user = factory('App\Models\User')->create();
        Passport::actingAs($user);

        #Getting list of Ocurrence Types
        $response = $this->getJson('api/ocurrence_types');

        #Assertion
        $response->assertStatus(200);
    }

    /** @test */
    public function check_if_storing_is_successful()
    {
        #Creating and acting as User
        $user = factory('App\Models\User')->create();
        Passport::actingAs($user);        

        #Posting data as method requests
        $response = $this->postJson('api/ocurrence_types', [
            'name' => $this->faker->name,
            'status' => implode($this->faker->randomElements([
                'leve', 'media', 'pesada'
            ])),
        ]);
        
        #Assertions
        $response->assertJsonFragment(['success' => true]);
        $response->assertStatus(200);        
    }

    /** @test */
    public function check_if_storing_is_unsuccessful()
    {
        #Creating and acting as User
        $user = factory('App\Models\User')->create();
        Passport::actingAs($user);        

        #Posting data as method DOESN'T request (minus name)
        $response = $this->postJson('api/ocurrence_types', [
            'status' => implode($this->faker->randomElements([
                'leve', 'media', 'pesada'
            ])),
        ]);
        
        #Assertions
        $response->assertJson(['message' => 'Undefined index: name']);
        $response->assertStatus(500);        
    }

    /** @test */
    public function check_if_updating_is_succesful()
    {
        #Creating and acting as User
        $user = factory('App\Models\User')->create();
        Passport::actingAs($user); 

        #Creating Ocurrence Type to check update()
        $type = factory('App\Models\OcurrenceType')->create();

        #Putting data as method requests
        $response = $this->putJson('api/ocurrence_types/' . $type->id, [
            'name' => $this->faker->name,
            'status' => implode($this->faker->randomElements([
                'leve', 'media', 'pesada'
            ]))
        ]);

        #Assertions
        $response->assertJsonFragment(['success' => true]);
        $response->assertStatus(200);
    }

    /** @test */
    /*public function check_if_updating_is_unsuccesful()
    {
        #Creating and acting as User
        $user = factory('App\Models\User')->create();
        Passport::actingAs($user); 

        #Creating Ocurrence Type to check update()
        $type = factory('App\Models\OcurrenceType')->create();

        #Putting data as method DOESN'T request (passing missmatched keys)
        $response = $this->putJson('api/ocurrence_types/' . $type->id, [
            'last_name' => $this->faker->name,
            'status' => implode($this->faker->randomElements([
                'leve', 'media', 'pesada'
            ]))            
        ]);
        dd($response);

        #Assertions
        $response->assertJsonFragment(['success' => true]);
        $response->assertStatus(200);
    }*/

    /** @test */
    public function check_if_delete_is_deleting()
    {  
        #Creating and acting as User
        $user = factory('App\Models\User')->create();
        Passport::actingAs($user); 

        #Creating Ocurrence Type to check delete()
        $type = factory('App\Models\OcurrenceType')->create();

        #Soft Deleting data
        $response = $this->deleteJson('api/ocurrence_types/' . $type->id);
        
        #Assertion
        $response->assertStatus(200);
    }

    /** @test */
    public function check_if_show_is_showing_ocurrence_type_by_id()
    {
        #Creating and acting as User
        $user = factory('App\Models\User')->create();
        Passport::actingAs($user); 

        #Creating Ocurrence Type to check show()
        $type = factory('App\Models\OcurrenceType')->create();

        #Getting Ocurrence Type by ID
        $response = $this->getJson('api/ocurrence_types/' . $type->id);
        
        #Assertion
        $response->assertStatus(200);
    }

    /** @test */
    public function check_if_change_status_is_changing()
    {
        #Creating and acting as User
        $user = factory('App\Models\User')->create();
        Passport::actingAs($user); 

        #Creating Ocurrence Type to check changeStatus()
        $type = factory('App\Models\OcurrenceType')->create();

        #Putting data to change status
        $response = $this->putJson('api/ocurrence_types/status/' . $type->id, [
            'status' => implode($this->faker->randomElements([
                'leve', 'media', 'pesada'
            ]))
        ]);

        #Assertions
        $response->assertStatus(200);
        $response->assertJsonFragment(['success' => true]);
    }
    
}
