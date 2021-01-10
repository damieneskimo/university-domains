<?php

namespace App\Jobs;

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
        try {
            $client = new Client;
            $uri = University::SOURCE_API . 'name=' . $this->university->name . '&country=' . $this->university->country;
            $res = $client->request('get', $uri);
            $data = json_decode($res->getBody()->getContents());

            if (is_array($data) && ! empty($data)) {
                $record = $data[0];
                $this->university->update([
                    'alpha_two_code' => $record->alpha_two_code,
                    'country' => $record->country,
                    'state_province' => $record->{'state-province'},
                    'name' => $record->name,
                    'domains' => $record->domains,
                    'ttl' => rand(5, 15)
                ]);
            } else {
                // remove from local database if record not exists any more in source api data
                $this->university->delete();
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Whoops! Something went wrong when retrieving data!'
            ], 503);
        }
    }
}
