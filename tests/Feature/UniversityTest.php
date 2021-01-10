<?php

namespace Tests\Feature;

use App\Jobs\UpdateUniversityCache;
use App\Listeners\UpdateUniversityRecord;
use App\Models\University;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class UniversityTest extends TestCase
{
    /**
     * test can get universities list.
     *
     * @return void
     */
    public function test_api_get_universities_list_by_country()
    {
        $response = $this->getJson('api/universities?country=New Zealand');

        $response->assertStatus(200);
    }

    public function test_api_get_one_university()
    {
        $firstUniversity = University::first();
        $response = $this->getJson('api/universities/' . $firstUniversity->id);

        $response->assertStatus(200);
    }
}
