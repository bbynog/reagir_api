<?php

namespace Tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Passport\Passport;
use App\Models\OcurrenceType;

class OcurrenceTypeControllerTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    private $ocurrenceType;

    public function setUp(): void
    {
        $this->ocurrenceType = app(OcurrenceType::class);
        parent::setUp();
    }

    /** @test */
    public function check_if_index_is_showing_list_of_ocurrence_types()
    {
        #Creating and acting as User
        $user = factory('App\Models\User')->create();
        Passport::actingAs($user);

        #Creating Ocurrence Types and creating a type variable
        $types = factory('App\Models\OcurrenceType', 5)->create();
        $type = factory('App\Models\OcurrenceType')->create();

        #Getting list of Ocurrence Types
        $response = $this->getJson('api/ocurrence_types');
        
        #Assertions
        $response->assertStatus(200);
        $response->assertJsonFragment([$type->name]);
        #$response->assertJsonFragment([$types->find(2)->status]);
    }

    /** @test */
    public function check_if_storing_is_successful()
    {
        #Creating and acting as User
        $user = factory('App\Models\User')->create();
        Passport::actingAs($user);        

        #Posting data as method requests
        $name = $this->faker->name;
        $status = $this->faker->randomElement([
            'leve', 'media', 'pesada'
        ]);
        
        $response = $this->postJson('api/ocurrence_types', [
            'name' => $name,
            'status' => $status,
        ]);
        
        #Assertions
        $response->assertJson(['success' => true, 'data' => [
            'name'=> $name,
            'status' => $status,
        ]]);
        $response->assertStatus(200);      
    }

    /** @test */
    public function check_if_storing_validation_is_unsuccessful()
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
        $response->assertJsonFragment(['The name field is required.']);
        $response->assertStatus(422);        
    }

    /** @test */
    public function check_if_updating_is_successful()
    {
        #Creating and acting as User
        $user = factory('App\Models\User')->create();
        Passport::actingAs($user); 

        #Creating Ocurrence Type to check update()
        $type = factory('App\Models\OcurrenceType')->create();

        #Creating variables
        $name = $this->faker->name;

        #Putting data as method requests
        $response = $this->putJson('api/ocurrence_types/' . $type->id, [
            'name' => $name,
            'status' => $this->faker->randomElement([
                'leve', 'media', 'pesada'
            ])
        ]);

        #Assertions 
        $response->assertStatus(200);
        $response->assertJsonFragment([$name]);
        $response->assertJsonFragment(['success' => true]);
    }

    /** @test */
    public function check_if_updating_ocurrence_type_is_unsuccessful()
    {
        #Creating and acting as User
        $user = factory('App\Models\User')->create();
        Passport::actingAs($user); 

        #Creating Ocurrence Type to check update()
        $type = factory('App\Models\OcurrenceType')->create();

        #Putting data as method DOESN'T request (passing missmatched keys)
        $response = $this->putJson('api/ocurrence_types/' . $type->id, [
            'last_name' => $this->faker->name,           
        ]);

        #Assertions
        $response->assertJsonFragment(['success' => false]);
        $response->assertStatus(422);
    }

    /** @test */
    public function check_if_delete_ocurrence_type_is_deleting()
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
        $response->assertJsonFragment(['success' => true]);
        $response->assertJsonFragment([$type->name]);
    }

    /** @test */
    public function check_if_delete_ocurrence_type_is_throwing_error_wrong_id()
    {  
        #Creating and acting as User
        $user = factory('App\Models\User')->create();
        Passport::actingAs($user); 

        #Soft Deleting data
        $response = $this->deleteJson('api/ocurrence_types/999');
        
        #Assertion
        $response->assertStatus(422);
        $response->assertJsonFragment(['success' => false]);
        $response->assertJsonFragment(['data' => 'Inexistent Type ID. Please enter a valid one.']);
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
        $response->assertJsonFragment([$type->name]);
        $response->assertJsonFragment([$type->status]);
    }

    /** @test */
    public function check_if_change_status_is_changing()
    {
        #Creating and acting as User
        $user = factory('App\Models\User')->create();
        Passport::actingAs($user); 

        #Creating Ocurrence Type to check changeStatus()
        $type = factory('App\Models\OcurrenceType')->create();

        #Creating variables
        $status = $this->faker->randomElement([
            'leve', 'media', 'pesada'
        ]);

        #Putting data to change status
        $response = $this->putJson('api/ocurrence_types/status/' . $type->id, [
            'status' => $status
        ]);

        #Assertions
        $response->assertStatus(200);
        $response->assertJsonFragment(['success' => true]);
        $response->assertJsonFragment([$status]);
    }
    
}
