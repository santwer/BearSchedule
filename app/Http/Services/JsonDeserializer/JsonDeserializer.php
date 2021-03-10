<?php


namespace App\Http\Services\JsonDeserializer;


use Illuminate\Database\Eloquent\Model;
use Psy\Util\Json;

class JsonDeserializer
{
    private array $json = [];
    private string $model = "";
    protected $collection = null;

    public static function deserialize(array $json, string $model):\Illuminate\Support\Collection
    {
        $deserialier = new JsonDeserializer($json, $model);

        $data = $deserialier->getCollection();
        if($data === null) {
            return collect();
        }
        return $data;
    }

    public function __construct(array $json, string $model)
    {
        $this->json = $json;
        $this->model = $model;
        $this->getModel();
        $this->validateData();
    }

    private function getModel()
    {
        if(!class_exists($this->model)) {
            throw new \Exception();
        }
    }

    private function validateData()
    {
        $this->collection = collect();
        foreach($this->json as $data) {
            $model = new $this->model;
            $model->deserialize($data);
            $this->collection->push($model);
        }
    }

    public function getCollection():?\Illuminate\Support\Collection
    {
        return $this->collection;
    }

}
