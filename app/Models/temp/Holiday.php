<?php

namespace App\Models\temp;

use App\Http\Services\JsonDeserializer\ModelCasts;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use ModelCasts;

    protected $casts = [
        'name'          => 'string',
        'name_local'    => 'string',
        'language'      => 'string',
        'description'   => 'string',
        'country'       => 'string',
        'location'      => 'string',
        'type'          => 'string',
        'date'          => 'date:M/D/Y',
        'date_year'     => 'int',
        'date_month'    => 'int',
        'date_day'      => 'int',
        'week_day'      => 'string',
    ];
}
