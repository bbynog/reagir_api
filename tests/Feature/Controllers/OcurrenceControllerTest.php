<?php

namespace Tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Passport\Passport;
use App\Models\Ocurrence;

class OcurrenceControllerTest extends TestCase
{   
    use WithFaker;
    use RefreshDatabase;

    private $ocurrence;

    public function setUp(): void
    {
        $this->ocurrence = app(Ocurrence::class);
        parent::setUp();
    }

    /** @test */
    public function check_if_show_method_is_showing_by_id()
    {       
        #Creating and acting as User
        $user = factory('App\Models\User')->create();
        Passport::actingAs($user);

        #Creating Ocurrence Type
        $type = factory('App\Models\OcurrenceType')->create();
          
        #Creating Ocurrence and storing into variable
        $ocurrence = factory('App\Models\Ocurrence')->create();        

        #Calling getJson and storing response into variable
        $response = $this->getJson('/api/ocurrences/' . $ocurrence->id);
        
        #Creating variables
        $data = $response->decodeResponseJson();
        
        #Assertions
        $response->assertStatus(200);

        #Assert Json
        $response->assertJson([
            0 => [
                'id' => $ocurrence->id,
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email
                ],
                'type' => [
                    'name' => $type->name,
                    'status' => $type->status
                ],
                'violence_type' => $ocurrence->violence_type,
                'what_to_do' => $ocurrence->what_to_do
        ]]);
    }

    /** @test */
    public function check_if_index_is_showing_all_ocurrences()
    {
        #Creating and acting as User
        $user = factory('App\Models\User')->create();
        Passport::actingAs($user);

        #Creating Ocurrence Type
        factory('App\Models\OcurrenceType')->create();

        #Creating Ocurrences
        $ocurrences = factory('App\Models\Ocurrence', 5)->create();

        #Calling getJson and storing response into variable
        $response = $this->getJson('/api/ocurrences/');
        $response_decoded = $response->decodeResponseJson();

        #Assertion
        $response->assertStatus(200);
        $this->assertCount(5, $response_decoded);
        
        for ($i=0; $i < count($ocurrences); $i++) {
            $this->assertEquals($ocurrences[$i]->violence_type, $response_decoded[$i]['violence_type']);
            $this->assertEquals($ocurrences[$i]->what_to_do, $response_decoded[$i]['what_to_do']);
        }
    }

    /** @test */
    public function check_if_store_ocurrences_is_successful()
    {
        #Creating and acting as User
        $user = factory('App\Models\User')->create();
        Passport::actingAs($user);

        #Creating Ocurrence Type to pass type_name
        $type = factory('App\Models\OcurrenceType')->create();

        #Creating variables
        $violence_type = $this->faker->name;
        $what_to_do = $this->faker->paragraph;

        #Calling postJson with required data and storing into variable
        $response = $this->postJson('api/ocurrences', [
            'violence_type' => $violence_type,
            'what_to_do' => $what_to_do,
            'type_name' => $type->name
        ]);
        
        #Assertion
        $response->assertStatus(200); 
        $response->assertJson(['success' => true, 'data' => [
            'violence_type'=> $violence_type,
            'what_to_do' => $what_to_do,
        ]]);            
    }

    /** @test */
    public function check_if_store_validation_is_unsuccessful()
    {
        #Creating and acting as User
        $user = factory('App\Models\User')->create();
        Passport::actingAs($user);

        #Creating Ocurrence Type to pass type_name
        $type = factory('App\Models\OcurrenceType')->create();

        #Calling postJson with missing data(what_to_do) and storing into variable
        $response = $this->postJson('api/ocurrences', [
            'violence_type' => $this->faker->name,
            'type_name' => $type->name
        ]);

        #Creating validation message variable
        $message = "The what to do field is required.";

        #Assertion
        $response->assertStatus(422);  
        $response->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'what_to_do' => [
                    0 => $message
                ]
            ]
        ]);     
    }

    /** @test */
    public function check_if_update_in_ocurrence_controller_is_successful()
    {
        #Creating and acting as User
        $user = factory('App\Models\User')->create();
        Passport::actingAs($user);

        #Creating Ocurrence Type
        $type = factory('App\Models\OcurrenceType')->create();

        #Creating Ocurrence to get ID
        $ocurrence = factory('App\Models\Ocurrence')->create();

        #Creating variables
        $what_to_do = $this->faker->paragraph;

        #Calling putJson and storing into variable
        $response = $this->putJson('api/ocurrences/' . $ocurrence->id, [
            'what_to_do' => $what_to_do
        ]);

        #Assertion
        $response->assertStatus(200); 
        $response->assertJson([            
            'data' => [
                'id' => $ocurrence->id,
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email
                ],
                'type' => [
                    'name' => $type->name,
                    'status' => $type->status
                ],
                'violence_type' => $ocurrence->violence_type,
                'what_to_do' => $what_to_do
            ],
            'success' => true
        ]);     
    }

    /** @test */
    public function check_if_updating_ocurrence_is_unsuccessful()
    {
        #Creating and acting as User
        $user = factory('App\Models\User')->create();
        Passport::actingAs($user); 

        #Creating Ocurrence Type
        factory('App\Models\OcurrenceType')->create();

        #Creating Ocurrence to test update()
        $ocurrence = factory('App\Models\Ocurrence')->create();

        #Putting data as method DOESN'T request (passing missmatched keys)
        $response = $this->putJson('api/ocurrences/' . $ocurrence->id, [
            'last_name' => $this->faker->name,           
        ]);

        #Warning message
        $message = "Need to choose at least one field to update. [violence_type, what_to_do, type_name]";

        #Assertions
        $response->assertStatus(422);
        $response->assertJson([
            'data' => $message,
            'success' => false
        ]);
    }

    /** @test */
    public function check_if_delete_ocurrences_is_successful()
    {
        #Creating and acting as User
        $user = factory('App\Models\User')->create();
        Passport::actingAs($user);

        #Creating Ocurrence Type
        factory('App\Models\OcurrenceType')->create();

        #Creating Ocurrence to get ID
        $ocurrence = factory('App\Models\Ocurrence')->create();

        #Calling deleteJson and storing into variable
        $response = $this->deleteJson('api/ocurrences/' . $ocurrence->id);

        #Creating variable to count
        $all = $this->ocurrence->all();

        #Assertion
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertCount(0, $all);
    }

    /** @test */
    public function check_if_delete_ocurrences_is_throwing_error_wrong_id()
    {  
        #Creating and acting as User
        $user = factory('App\Models\User')->create();
        Passport::actingAs($user); 

        #Soft Deleting data passing wrong ID
        $response = $this->deleteJson('api/ocurrences/999');
        
        #Assertion
        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'data' => 'Inexistent Ocurrence ID. Please enter a valid one.'
            ]);
    }
}
