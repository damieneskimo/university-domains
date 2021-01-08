<?php

namespace App\Listeners;

use App\Events\UniversityCacheExpired;
use App\Models\University;
use GuzzleHttp\Client;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateUniversityRecord
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\UniversityCacheExpired  $event
     * @return void
     */
    public function handle(UniversityCacheExpired $event)
    {
        try {
            $client = new Client;
            $uri = University::SOURCE_API . 'name=' . $event->university->name . '&country=' . $event->university->country;
            $res = $client->request('get', $uri);
            $data = json_decode($res->getBody()->getContents());

            if (is_array($data) && ! empty($data)) {
                $record = $data[0];
                $event->university->update([
                    'alpha_two_code' => $record->alpha_two_code,
                    'country' => $record->country,
                    'state_province' => $record->{'state-province'},
                    'name' => $record->name,
                    'domains' => $record->domains,
                    'ttl' => rand(5, 15)
                ]);
            } else {
                // remove from local database if record not exists any more in source api data
                $event->university->delete();
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Whoops! Something went wrong when retrieving data!'
            ], 503);
        }
    }
}
