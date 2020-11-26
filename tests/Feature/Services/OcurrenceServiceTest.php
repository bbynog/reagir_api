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

        #Assertion
        $this->assertEquals($save['success'], true);
        $this->assertContains($violence_type, $save); # Seja especifico, utilize $this->assertEquals()
        $this->assertContains($what_to_do, $save);    # Seja especifico, utilize $this->assertEquals()
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

        #Creating Data to pass into update()
        $data = [
            'what_to_do' => $what_to_do
        ];

        #Calling update() and storing response into variable
        $update = $this->service->update($data, $ocurrence->id); #Por que ao inves de criar um array $data la em cima pra jogar aqui, vc n usa ['what_to_do' => $what_to_do]?

        #Assertion
        $this->assertEquals($update['response']['success'], true);
        $this->assertInstanceOf(OcurrenceResource::class, $update['response']['data']);
        $this->assertContains($what_to_do, $update['response']); # Seja especifico, utilize $this->assertEquals()
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
        $this->assertInstanceOf(Ocurrence::class, $delete['response']['data']);
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
        factory('App\Models\Ocurrence', 5)->create();

        #Calling method list() and storing response into a variable
        $list = $this->service->list();

        #Assertion
        $this->assertCount(5, $list);

        #Rolava fazer um for() aqui e utilizar o $this->assertEquals
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
        # Usar $this->assertEquals()
    }

    # Criar cenário de erro para o show (por exemplo se não vier um ID
}
