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
        $save = $this->service->save($data);

        #Assertions
        $this->assertContains($data, $save);
        $this->assertEquals(true, $save['success']);
        $this->assertInstanceOf(OcurrenceType::class, $save['data']);
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
        $save = $this->service->save($data);

        #Assertions
        $this->assertEquals($invalid_status, $save['data']);
        $this->assertEquals(false, $save['success']);
    }

    /** @test */
    public function check_if_update_is_successful()
    {
        #Creating Ocurrence Type to check update()
        $type = factory('App\Models\OcurrenceType')->create();

        #Creating variables
        $name = $this->faker->name;
        $status = $this->faker->randomElement([
            'leve', 'media', 'pesada'
        ]);

        #Creating Data as required to check update()
        $data = [
            'name' => $name,
            'status' => $status
        ];

        #Calling update() and storing in a variable
        $update = $this->service->update($data, $type->id);

        #Assertion
        $this->assertEquals(true, $update['response']['success']);
        $this->assertContains($name, $update['response']); # Seja especifico, utilize $this->assertEquals()
        $this->assertContains($status, $update['response']); # Seja especifico, utilize $this->assertEquals()
        $this->assertInstanceOf(OcurrenceType::class, $update['response']['data']);
    }

    /** @test */
    public function check_if_update_validation_of_status_is_unsuccesful()
    {
        #Creating Ocurrence Type to check update()
        $type = factory('App\Models\OcurrenceType')->create();

        #Creating Data with [status] field invalid to check update() validation
        $data = [
            'name' => $this->faker->name,
            'status' => $this->faker->randomElement([
                'light', 'medium', 'heavy'
            ])
        ];

        #Calling update() and storing in a variable
        $update = $this->service->update($data, $type->id);

        #Message of Invalid Type stored in a variable to check assertion
        $message = "Invalid Status Type. Accept only ['leve', 'media', 'pesada'].";

        #Assertions
        $this->assertEquals($message, $update['response']['data']);
        $this->assertEquals(false, $update['response']['success']);
    }

    /** @test */
    public function check_if_delete_type_is_softdeleting()
    {
        #Creating Ocurrence Type to check delete()
        $type = factory('App\Models\OcurrenceType')->create();

        #Calling delete() and storing the response
        $delete = $this->service->delete($type->id);

        #Assertion
        $this->assertEquals(true, $delete['response']['success']);
        $this->assertInstanceOf(OcurrenceType::class, $delete['response']['data']);
        $this->assertEquals(200, $delete['status_code']);
    }

    /** @test */
    public function check_if_list_is_showing_all_ocurrence_types()
    {
        #Creating Types
        factory('App\Models\OcurrenceType', 5)->create();

         #Calling method list() that gives all Ocurrence Types
        $list = $this->service->list();

        #Assertion
        $this->assertCount(5, $list);
    }

    /** @test */
    public function check_if_show_is_showing_by_id()
    {
        #Creating Ocurrence Type to check show()
        $type = factory('App\Models\OcurrenceType')->create(['name' => 'teste']);

        #Calling method show() and saving into variable
        $show = $this->service->show($type->id);

        #Assertion
        $this->assertInstanceOf(OcurrenceType::class, $show);
        $this->assertEquals('teste', $show->name);
    }

    # Criar cenário de erro para o show (por exemplo se não vier um ID

    /** @test */
    public function check_if_change_status_is_successful()
    {
        #Creating Ocurrence Type to check changeStatus()
        $type = factory('App\Models\OcurrenceType')->create();

        #Creating variables to compare
        $status = $this->faker->randomElement([
            'leve', 'media', 'pesada'
        ]);

        #Creating data as required to change status
        $data = [
            'status' => $status
        ];

        #Calling method changeStatus() and storing into variable
        $status_r = $this->service->changeStatus($data['status'], $type->id);

        #Assertion
        $this->assertEquals(true, $status_r['success']);
        $this->assertInstanceOf(OcurrenceType::class, $status_r['data']);
        $this->assertEquals($status, $status_r['data']->status);
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
        $change_status = $this->service->changeStatus($data['status'], $type->id);

        #Invalid Status message
        $message = "Invalid Status Type. Accept only ['leve', 'media', 'pesada'].";

        #Assertion
        $this->assertEquals(false, $change_status['success']);
        $this->assertEquals($message, $change_status['data']);
    }

    /** @test */
    public function check_if_validate_status_is_successful()
    {
        #Creating data as required to check validadeStatus()
        $data = [
            'status' => $this->faker->randomElement([
                'leve', 'media', 'pesada'
            ])
        ];

        #Calling validadeStatus() and storing response into variable
        $validate_status = $this->service->validateStatus($data['status']);

        #Assertion
        $this->assertEquals(true, $validate_status);
    }

    /** @test */
    public function check_if_validate_status_is_unsuccessful()
    {
        #Creating data as NOT required to check validadeStatus()
        $data = [
            'status' => $this->faker->randomElement([
                'light', 'medium', 'heavy'
            ])
        ];

        #Calling validadeStatus() and storing response into variable
        $validate_status = $this->service->validateStatus($data['status']);

        #Assertion
        $this->assertEquals(false, $validate_status);
    }

    /** @test */
    public function check_if_get_type_is_getting_by_name()
    {
        #Creating Ocurrence Type to check getType()
        $type = factory('App\Models\OcurrenceType')->create();

        #Calling getType and storing response into variable
        $get_type = $this->service->getType($type->name);

        #Assertion
        $this->assertEquals($get_type->id, $type->id);
        $this->assertInstanceOf(OcurrenceType::class, $get_type);
    }
}
