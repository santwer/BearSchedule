<?php


namespace App\Helper;


use Ramsey\Uuid\Uuid;

class TempApiKey
{
    static public function get():string
    {
        $uuid = Uuid::uuid4()->toString();
        session()->put('api_key', $uuid);
        session()->save();
        return $uuid;
    }

    static public function check(string $key):bool
    {
        return session()->get('api_key') === $key;
    }

}
