<?php

namespace App\Jobs;

use App\Events\UniversityCacheDeleted;
use App\Events\UniversityCacheUpdated;
use App\Models\University;
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
        $data = University::getUniversityByCountryAndNameFromAPI($this->university->country, $this->university->name);
        $data = json_decode($data);

        if (! empty($data) && is_array($data)) {
            $universityFromAPI = $data[0];

            if ($universityFromAPI->domains != $this->university->domains) {
                // if record domains from api is different from local, then update record
                $this->university->domains = $universityFromAPI->domains;
                $this->university->ttl = rand(5, 15);
                $this->university->save();

                // then broadcast the event to the UI
                UniversityCacheUpdated::dispatch($this->university);
            }
        } else {
            $originalId = $this->university->id;
            // if it's empty, means the university doesn't exist in the source anymore, so delete the record
            $this->university->delete();

            // then broadcast the delete event to the UI
            UniversityCacheDeleted::dispatch($originalId);
        }


    }
}
