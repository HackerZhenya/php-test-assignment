<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function showUsers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'age.*' => 'numeric',
            'geo_location.*.*' => 'numeric',
            'gender' => 'in:male,female',
            'hobby' => 'array',
        ]);

        if ($validator->fails())
            return ["errors" => $validator->errors()->all()];

        $data = $validator->validated();
        $model = new \App\User;

        return $model
            ->byGeoLocation($data['geo_location']['nw'] ?? null, $data['geo_location']['se'] ?? null)
            ->byAgeRange($data['age']['from'] ?? null, $data['age']['to'] ?? null)
            ->byGender($data['gender'] ?? null)
            ->byHobby($data['hobby'] ?? null)
            ->get();
    }
}
