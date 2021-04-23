<?php

namespace App\Models\Holidays;

use App\Http\Services\JsonDeserializer\ModelCasts;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use ModelCasts;

}
