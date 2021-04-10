<?php


namespace App\Http\Services\Settings;


use antwersv\jsonDeserializer\JsonDeserializer;
use App\Models\temp\Holiday;
use Illuminate\Support\Facades\Http;

class Holidays
{

    public static function getHolidays()
    {
        $response = Http::get('http://127.0.0.1:8000/test.json');
        $equil = JsonDeserializer::deserialize(
            $response->json(),
            [\App\Deserialize\Holiday::class]
        );
        $first = $equil->first();
        dd($first->save());
    }
}
