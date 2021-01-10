<?php

namespace Tests\Unit;

use App\Models\University;
use Tests\TestCase;

class UniversityTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_can_insert_a_university()
    {
        $university = University::factory()->make([
            'name' => 'Ara',
        ]);

        $this->assertInstanceOf(University::class, $university);
        $this->assertEquals($university->name, 'Ara');
        $this->assertTrue(true);
    }
}
