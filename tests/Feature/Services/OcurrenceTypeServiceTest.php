<?php

namespace Tests\Feature\Services;

use App\Models\OcurrenceType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Services\OcurrenceTypeService;

class OcurrenceTypeServiceTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    private $ocurrenceType;
    private $service;

    public function setUp(): void
    {
        $this->ocurrenceType = app(OcurrenceType::class);
        $this->service = app(OcurrenceTypeService::class);
        parent::setUp();
    }

    /** @test */
    public function check_if_save_type_service_is_successful()
    {    
        #Storing data as required on method save() from service       
        $data = [
            'name' => $this->faker->name,
            'status' => $this->faker->randomElement([
                'leve', 'media', 'pesada'
            ])
        ];

        #Calling method save() from service and storing into a variable
        $save_response = $this->service->save($data);
        
        #Assertions
        $this->assertContains($data, $save_response); 
        $this->assertEquals(true, $save_response['success']);
    }

    /** @test */
    public function check_if_save_type_service_validation_is_unsuccessful()
    {    
        #Storing data as NOT required on method save() validation from service       
        $data = [
            'name' => $this->faker->name,
            'status' => $this->faker->randomElement([
                'light', 'medium', 'heavy'
            ])
        ];

        $invalid_status = "Invalid Status Type. Accept only ['leve', 'media', 'pesada'].";

        #Calling method save() from service
        $save_response = $this->service->save($data);        
                
        #Assertions
        $this->assertEquals($invalid_status, $save_response['data']);
        $this->assertEquals(false, $save_response['success']);
    }

    /** @test */
    public function check_if_update_is_successful()
    {
        #Creating Ocurrence Type to check update()
        $type = factory('App\Models\OcurrenceType')->create();

        #Creating Data as required to check update()
        $data = [
            'name' => $this->faker->name,
            'status' => implode($this->faker->randomElements([
                'leve', 'media', 'pesada'
            ]))
        ];

        #Calling update() and storing in a variable
        $update_response = $this->service->update($data, $type->id);

        #Assertion
        $this->assertEquals(true, $update_response['response']['success']);
    }

    /** @test */
    public function check_if_update_validation_of_status_is_unsuccesful()
    {
        #Creating Ocurrence Type to check update()
        $type = factory('App\Models\OcurrenceType')->create();

        #Creating Data with [status] field invalid to check update() validation
        $data = [
            'name' => $this->faker->name,
            'status' => implode($this->faker->randomElements([
                'light', 'medium', 'heavy'
            ]))
        ];

        #Calling update() and storing in a variable
        $update_response = $this->service->update($data, $type->id);

        #Message of Invalid Type stored in a variable to check assertion
        $message = "Invalid Status Type. Accept only ['leve', 'media', 'pesada'].";

        #Assertions
        $this->assertEquals($message, $update_response['response']['data']);
        $this->assertEquals(false, $update_response['response']['success']);
    }

    /** @test */
    public function check_if_delete_is_softdeleting()
    {
        #Creating Ocurrence Type to check delete()
        $type = factory('App\Models\OcurrenceType')->create();

        #Calling delete() and storing the response
        $delete_response = $this->service->delete($type->id);

        #Assertion
        $this->assertEquals(true, $delete_response['response']['success']);        
    }

    /** @test */
    public function check_if_list_is_showing_all_ocurrence_types()
    {
        #Calling all Ocurrence Types into a variable
        $all_types = $this->ocurrenceType->all();

        #Calling method list() that gives all Ocurrence Types
        $list_response = $this->service->list();

        #Assertion
        $this->assertEquals($all_types, $list_response);
    }

    /** @test */
    public function check_if_show_is_showing_by_id()
    {
        #Creating Ocurrence Type to check show()
        $type = factory('App\Models\OcurrenceType')->create();

        #Searching in database to compare in assertion
        $find_to_compare = $this->ocurrenceType->find($type->id);

        #Calling method show() and saving into variable
        $show_response = $this->service->show($type->id);

        #Assertion
        $this->assertEquals($find_to_compare, $show_response);
    }

    /** @test */
    public function check_if_change_status_is_successful()
    {
        #Creating Ocurrence Type to check changeStatus()
        $type = factory('App\Models\OcurrenceType')->create();

        #Creating data as required to change status
        $data = [
            'status' => implode($this->faker->randomElements([
                'leve', 'media', 'pesada'
            ]))
        ];

        #Calling method changeStatus() and storing into variable
        $response = $this->service->changeStatus($data['status'], $type->id);

        #Assertion
        $this->assertEquals(true, $response['success']);
    }

    /** @test */
    public function check_if_change_status_validation_is_unsuccesful()
    {
        #Creating Ocurrence Type to check changeStatus()
        $type = factory('App\Models\OcurrenceType')->create();

        #Creating data as NOT required to test validation
        $data = [
            'status' => implode($this->faker->randomElements([
                'light', 'medum', 'heavy'
            ]))
        ];

        #Calling method changeStatus() and storing into variable
        $response = $this->service->changeStatus($data['status'], $type->id);

        #Invalid Status message
        $message = "Invalid Status Type. Accept only ['leve', 'media', 'pesada'].";

        #Assertion
        $this->assertEquals(false, $response['success']);
        $this->assertEquals($message, $response['data']);
    }

    /** @test */
    public function check_if_validate_status_is_successful()
    {
        #Creating data as required to check validadeStatus()
        $data = [
            'status' => implode($this->faker->randomElements([
                'leve', 'media', 'pesada'
            ]))
        ];

        #Calling validadeStatus() and storing response into variable
        $response = $this->service->validateStatus($data['status']);

        #Assertion
        $this->assertEquals(true, $response);
    }

    /** @test */
    public function check_if_validate_status_is_unsuccessful()
    {
        #Creating data as NOT required to check validadeStatus()
        $data = [
            'status' => implode($this->faker->randomElements([
                'light', 'medium', 'heavy'
            ]))
        ];

        #Calling validadeStatus() and storing response into variable
        $response = $this->service->validateStatus($data['status']);

        #Assertion
        $this->assertEquals(false, $response);
    }

    /** @test */
    public function check_if_get_type_is_getting_by_name()
    {
        #Creating Ocurrence Type to check getType()
        $type = factory('App\Models\OcurrenceType')->create();        

        #Calling getType and storing response into variable
        $response = $this->service->getType($type->name);

        #Assertion
        $this->assertEquals($response->id, $type->id);
    }
}
