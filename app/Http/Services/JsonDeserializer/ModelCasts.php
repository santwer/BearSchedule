<?php


namespace App\Http\Services\JsonDeserializer;


use Carbon\Carbon;
use Carbon\Traits\Date;
use Ramsey\Uuid\Uuid;

trait ModelCasts
{
    protected bool $strictDeserialize = true;

    public function deserialize(array $data)
    {
        $casts = $this->getValidCasts();

        foreach($data as $key => $value) {

            if($this->strictDeserialize && !isset($casts[$key])) {
                throw new \Exception($key . ' not set in Model');
            }

            if($this->checkType($value, $casts[$key]) !== false) {
                $this->{$key} = $value;
            }


        }

    }

    public function getValidCasts()
    {
        return $this->casts;
    }

    public function checkType($value, string $type) {
        switch ($type) {
            case 'string':
                return is_string($value) ? (string)$value : false;
            case 'int':
                return is_numeric($value) ? (int)$value : false;
            case 'bool':
                return is_bool($value) ? (bool)$value : false;
            case 'array':
                return is_array($value) ? $value : false;
            case 'uuid':
                return Uuid::isValid($value) ? (string)$value : false;
        }
        $typePart = explode(':', $type);
        if($typePart[0] === 'date') {
            if($value === null || empty($value)) {
                return null;
            }
            return Carbon::parse($value);
        }
    }
}
