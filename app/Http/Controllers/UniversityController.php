<?php

namespace App\Http\Controllers;

use App\Models\University;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class UniversityController extends Controller
{
    public function index(Request $request)
    {
        $sourceApi = 'http://universities.hipolabs.com/search?country=';

        if ($request->filled('country')) {
            $universities = University::where('country', $request->country)->get();

            if ($universities->isEmpty()) {
                try {
                    $client = new Client;
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

                    $universities = $data;
                } catch (\Throwable $th) {
                    return response()->json([
                        'message' => 'Whoops! Something went wrong when retrieving data!'
                    ], 503);
                }
            }

            return $universities;
        } else {
            return response()->json([
                'message' => 'Please choose a country first!'
            ], 400);
        }
    }
}
