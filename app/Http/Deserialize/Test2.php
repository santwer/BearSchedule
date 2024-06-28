<?php

namespace App\Http\Deserialize;

use antwersv\jsonDeserializer\Deserialize\Deserialize;

class Test2 extends Deserialize
{
   //define all rules as public attributes
   //public string $example;

   //define with more Laravel Validation-Rules
   //public string $email = 'email';

    /**
    * usage example:
    *
    * $response = Http::get('http://127.0.0.1/someFile.json');
    * $equil = JsonDeserializer::deserialize(
    * $response->json(),
    * [Test2::class]
    * );
    */
}
