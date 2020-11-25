<?php

namespace Tests\Feature\Services;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Ocurrence;
use App\Services\OcurrenceService;
use App\Http\Resources\OcurrenceResource;
use Laravel\Passport\Passport;

class OcurrenceServiceTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    private $ocurrence;
    private $service;

    public function setUp(): void
    {
        $this->ocurrence = app(Ocurrence::class);
        $this->service = app(OcurrenceService::class);
        parent::setUp();
    }

    /** @test */
    public function check_if_save_ocurrence_is_saving()
    {
        #Creating a Ocurrence Type to pass name into save()
        $type = factory('App\Models\OcurrenceType')->create();

        #Creating a User to pass ID into save()
        $user = factory('App\Models\User')->create();

        #Storing data as required on method save() from service       
        $data = [
            'violence_type' => $this->faker->name,
            'what_to_do' => $this->faker->paragraph,
            'type_name' => $type->name,
            'user_id' => $user->id
        ];

        #Calling method save() and storing response into a variable
        $response = $this->service->save($data);
        
        #testar instanceof, valores
        #Assertion
        $this->assertEquals($response['success'], true);
    }

    /** @test */
    public function check_if_save_ocurrence_validation_is_unsuccessful()
    {
        #Creating a User to pass ID into save()
        $user = factory('App\Models\User')->create();

        #Storing wrongful data (type_name doesnt exists) on method save() from service       
        $data = [
            'violence_type' => $this->faker->name,
            'what_to_do' => $this->faker->paragraph,
            'type_name' => 'neveruse',
            'user_id' => $user->id
        ];

        #Calling method save() and storing response into a variable
        $response = $this->service->save($data);

        #Creating Invalid Type message variable
        $message = "Type doesn't exist.";

        #Assertion
        $this->assertEquals($response['success'], false);
        $this->assertEquals($response['data'], $message);
    }

    /** @test */
    public function check_if_update_ocurrence_is_successful()
    {       
        #Creating User and actingAs
        factory('App\Models\User')->create();

        #Creating Ocurrence Type
        factory('App\Models\OcurrenceType')->create();

        #Creating an Ocurrence to pass ID into update()
        $ocurrence = factory('App\Models\Ocurrence')->create();        

        #Creating Data to pass into update()
        $data = [
            'what_to_do' => $this->faker->paragraph
        ];

        #Calling update() and storing response into variable
        $response = $this->service->update($data, $ocurrence->id);

        #instanceof, variavel/faker
        #Assertion
        $this->assertEquals($response['response']['success'], true);
    }

    /** @test */
    public function check_if_delete_ocurrence_is_deleting()
    {
        #Creating User and actingAs
        factory('App\Models\User')->create();

        #Creating Ocurrence Type
        factory('App\Models\OcurrenceType')->create();

        #Creating an Ocurrence to pass ID into delete()
        $ocurrence = factory('App\Models\Ocurrence')->create();

        #Calling delete() and storing response into variable
        $response = $this->service->delete($ocurrence->id);

        #Assertion
        $this->assertEquals(true, $response['response']['success']);
    }

    /** @test */
    public function check_if_list_ocurrences_is_successful()
    {
        #Creating User and actingAs
        factory('App\Models\User')->create();

        #Creating Ocurrence Type
        factory('App\Models\OcurrenceType')->create();

        #Creating Ocurrences
        factory('App\Models\Ocurrence', 5)->create();

        #Calling method list() and storing response into a variable
        $response = $this->service->list();

        #Assertion
        $this->assertCount(5, $response);
    }

    /** @test */
    public function check_if_show_ocurrence_by_id_is_showing()
    {
        #Creating User and actingAs
        factory('App\Models\User')->create();

        #Creating Ocurrence Type
        factory('App\Models\OcurrenceType')->create();

        #Creating Ocurrence
        factory('App\Models\Ocurrence')->create();

        #Calling show() and storing response into a variable
        $response = $this->service->show(1);
        
        #Assertion
        $this->assertInstanceOf(OcurrenceResource::class, $response);
    }
}
