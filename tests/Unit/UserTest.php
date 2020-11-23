<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    
    
    /** @test */
    public function check_if_user_columns_are_correct()
    {
        $user = new User;

        $expected = [
            'name',
            'email',
            'password',
        ];

        $arrays_comparison = array_diff($expected, $user->getFillable());

        $this->assertEquals(0, count($arrays_comparison));
    }
}
