<?php

namespace Tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Passport\Passport;

class OcurrenceControllerTest extends TestCase
{   
    /** @test */
    public function check_if_ocurrences_are_being_showed_by_id()
    {       
        $user = factory('App\Models\User')->create();
        Passport::actingAs($user);
        

        factory('App\Models\OcurrenceType')->create();  

        $ocurrence = factory('App\Models\Ocurrence')->create();

        $response = $this->getJson('/api/ocurrences/' . $ocurrence->id)
                ->assertStatus(200);        
    }
}
