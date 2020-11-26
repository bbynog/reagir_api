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

        #Creating variables
        $violence_type = $this->faker->name;
        $what_to_do = $this->faker->paragraph;
        #Storing data as required on method save() from service
        $data = [
            'violence_type' => $violence_type,
            'what_to_do' => $what_to_do,
            'type_name' => $type->name,
            'user_id' => $user->id
        ];

        #Calling method save() and storing response into a variable
        $save = $this->service->save($data);
        #dd($save);
        
        #Assertion
        $this->assertEquals($save['success'], true); 
        $this->assertEquals($save['data']['violence_type'], $violence_type);
        $this->assertEquals($save['data']['what_to_do'], $what_to_do);    
        $this->assertInstanceOf(OcurrenceResource::class, $save['data']);   
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
        $save = $this->service->save($data);

        #Creating Invalid Type message variable
        $message = "Type doesn't exist.";

        #Assertion
        $this->assertEquals($save['success'], false);
        $this->assertEquals($save['data'], $message);
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

        #Creating Variables
        $what_to_do = $this->faker->paragraph;

        #Calling update() and storing response into variable
        $update = $this->service->update(['what_to_do' => $what_to_do], $ocurrence->id);

        #Assertion
        $this->assertEquals($update['response']['success'], true);
        $this->assertInstanceOf(OcurrenceResource::class, $update['response']['data']);
        $this->assertEquals($update['response']['data']['what_to_do'], $what_to_do);
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
        $delete = $this->service->delete($ocurrence->id);

        #Assertion
        $this->assertEquals(true, $delete['response']['success']);
        $this->assertEquals(200, $delete['status_code']);
    }

    /** @test */
    public function check_if_delete_ocurrence_is_throwing_error_wrong_id()
    {
        #Creating User
        factory('App\Models\User')->create();

        #Creating Ocurrence Type
        factory('App\Models\OcurrenceType')->create();

        #Calling delete() and storing response into variable
        $delete = $this->service->delete(999);

        #Wrong ID message
        $message = "Inexistent Ocurrence ID. Please enter a valid one.";

        #Assertion
        $this->assertEquals(false, $delete['response']['success']);
        $this->assertEquals($message, $delete['response']['data']);
        $this->assertEquals(422, $delete['status_code']);
    }

    # Rolava criar um test pra cobrir um cenário de erro do método delete()

    /** @test */
    public function check_if_list_ocurrences_is_successful()
    {
        #Creating User and actingAs
        factory('App\Models\User')->create();

        #Creating Ocurrence Type
        factory('App\Models\OcurrenceType')->create();

        #Creating Ocurrences
        $ocurrences = factory('App\Models\Ocurrence', 5)->create();

        #Calling method list() and storing response into a variable
        $list = $this->service->list();

        #Assertion
        $this->assertCount(5, $list);

        for ($i=0; $i < count($ocurrences); $i++) {
            $this->assertEquals($ocurrences[$i]->violence_type, $list[$i]['violence_type']);
            $this->assertEquals($ocurrences[$i]->what_to_do, $list[$i]['what_to_do']);
        }
    }

    /** @test */
    public function check_if_show_ocurrence_by_id_is_showing()
    {
        #Creating User and actingAs
        factory('App\Models\User')->create();

        #Creating Ocurrence Type
        factory('App\Models\OcurrenceType')->create();

        #Creating Ocurrence
        $ocurrence = factory('App\Models\Ocurrence')->create();

        #Calling show() and storing response into a variable
        $show = $this->service->show($ocurrence->id);

        #Assertion
        $this->assertInstanceOf(OcurrenceResource::class, $show['response']['data']);
        $this->assertEquals($show['response']['data']['violence_type'], $ocurrence->violence_type);
        $this->assertEquals($show['response']['data']['what_to_do'], $ocurrence->what_to_do);
    }

    # Criar cenário de erro para o show (por exemplo se não vier um ID
}
