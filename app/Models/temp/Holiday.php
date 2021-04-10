<?php

namespace App\Models\temp;

use App\Http\Services\JsonDeserializer\ModelCasts;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use ModelCasts;

}
