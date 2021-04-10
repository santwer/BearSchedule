<?php


namespace App\Deserialize;

use antwersv\jsonDeserializer\Deserialize\Deserialize;
use Carbon\Carbon;

class Holiday extends Deserialize
{
    protected $model = \App\Models\temp\Holiday::class;


    public string $name;
    public string $name_local;
    public string $language;
    public string $description;
    public string $country;
    public string $location;
    public string $type;
    public Carbon $date;
    public int $date_year;
    public int $date_month;
    public int $date_day;
    public string $week_day;




}
