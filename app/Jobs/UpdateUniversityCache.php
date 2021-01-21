<?php

namespace App\Jobs;

use App\Events\UniversityCacheExpired;
use App\Models\University;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateUniversityCache implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $university;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(University $university)
    {
        $this->university = $university;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        UniversityCacheExpired::dispatch($this->university);
    }
}
