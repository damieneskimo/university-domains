<?php

namespace App\Http\Controllers;

use App\Models\University;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class UniversityController extends Controller
{
    public function search(Request $request)
    {
        if ($request->filled(['name', 'country'])) {
            $university = University::where('country', $request->country)->where('name', $request->name)->first();

            if (! is_null($university)) {
                return $university;
            } else {
                return response()->json([
                    'message' => 'Sorry! University not found!'
                ], 500);
            }
        }

        if ($request->filled('country')) {
            $universities = University::where('country', $request->country)->get();

            if ($universities->isEmpty()) {
                try {
                    $client = new Client;
                    $uri = University::SOURCE_API . 'country=' . $request->country;
                    $res = $client->request('get', $uri);
                    $data = json_decode($res->getBody()->getContents());

                    if (! empty($data)) {
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
                    } else {
                        return response()->json([
                            'message' => 'Whoops! Something went wrong when retrieving data!'
                        ], 500);
                    }
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
