<?php

namespace App\Http\Controllers;

use App\Http\Resources\UniversityResource;
use App\Models\University;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class UniversityController extends Controller
{
    public function index(Request $request)
    {
        $country = $request->country;

        if ($request->filled('country')) {
            $universities = University::where('country', $country)->get();

            if ($universities->isEmpty()) {
                //if universities in this country is not cached in local db, then request from api and cache to db
                try {
                    $data = University::getUniversitiesByCountryFromAPI($country);
                    $data = json_decode($data);

                    if (! empty($universitiesFromAPI)) {
                        foreach ($universitiesFromAPI as $university) {
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

                        $universities = University::where('country', $country)->get();
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

            return UniversityResource::collection($universities);
        } else {
            return response()->json([
                'message' => 'Please choose a country first!'
            ], 400);
        }
    }

    public function show(University $university, Request $request)
    {
        return new UniversityResource($university);
    }
}
