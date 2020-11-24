<?php

namespace Tests\Feature\Services;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Ocurrence;
use App\Services\OcurrenceService;
use App\Http\Resources\OcurrenceResource;

class OcurrenceServiceTest extends TestCase
{
    use WithFaker;

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
        #Creating an Ocurrence to pass ID into update()
        $ocurrence = factory('App\Models\Ocurrence')->create();

        #Creating Data to pass into update()
        $data = [
            'what_to_do' => $this->faker->paragraph
        ];

        #Calling update() and storing response into variable
        $response = $this->service->update($data, $ocurrence->id);

        #Assertion
        $this->assertEquals($response['success'], true);
    }

    /** @test */
    public function check_if_delete_ocurrence_is_deleting()
    {
        #Creating an Ocurrence to pass ID into delete()
        $ocurrence = factory('App\Models\Ocurrence')->create();

        #Calling delete() and storing response into variable
        $response = $this->service->delete($ocurrence->id);

        #Assertion
        $this->assertEquals($response, true);
    }

    /** @test */
    public function check_if_list_ocurrences_is_successful()
    {
        #Creating an Ocurrence instance to get all Ocurrences
        $all_ocurrences = OcurrenceResource::collection($this->ocurrence->all());

        #Calling method list() and storing response into a variable
        $response = $this->service->list();

        #Assertion
        $this->assertEquals($all_ocurrences, $response);
    }

    /** @test */
    public function check_if_show_ocurrence_by_id_is_showing()
    {
        #Creating an Ocurrence to pass ID into show()
        $ocurrence = $this->ocurrence->find(1);

        #Calling show() and storing response into a variable
        $response = $this->service->show($ocurrence->id);
        
        #Assertion
        $this->assertEquals(new OcurrenceResource($ocurrence), $response);
    }
}
