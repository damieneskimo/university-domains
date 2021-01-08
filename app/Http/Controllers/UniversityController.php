<?php

namespace App\Http\Controllers;

use App\Models\University;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class UniversityController extends Controller
{
    public function index(Request $request)
    {
        $sourceApi = 'http://universities.hipolabsfasfd.com/search?country=';

        if ($request->filled('country')) {
            $client = new Client;

            try {
                $res = $client->request('get', $sourceApi . $request->country);
                $data = json_decode($res->getBody()->getContents());

                foreach ($data as $university) {
                    University::updateOrCreate(
                        ['country' => $university->country, 'name' => $university->name],
                        [
                            'alpha_two_code' => $university->alpha_two_code,
                            'state_province' => $university->{'state-province'},
                            'domains' => $university->domains,
                            'ttl' => rand(5, 15)
                        ]
                    );
                }
            } catch (\Throwable $th) {
                //throw $th;
            }

            dd($data);
        } else {
            return response()->json([
                'message' => 'Please choose a country first!'
            ], 400);
        }
    }
}
