<?php


namespace App\Helper;


use Carbon\Carbon;

class ModelChangesHelper
{
    static public function getChangedValues($oldModel, $newModel)
    {
        $oldValues = [];
        $newValues = [];
        if(!empty($newModel->getAttributes()) && empty($oldModel)) {
            return ['', json_encode($newModel->getAttributes())];
        }
        $ignore = ['updated_at'];
        foreach ($newModel->getChanges() as $key => $value) {
            if (!in_array($key, $ignore)) {
                if ($value instanceof Carbon) {
                    $oldValue = Carbon::parse($oldModel[$key]);
                    if ($value->format('d.m.Y') != $oldValue->format('d.m.Y')) {
                        $oldValues[$key] = $oldValue->format('d.m.Y');
                        $newValues[$key] = $value->format('d.m.Y');
                    }
                } elseif ($oldModel[$key] !== $value) {
                    $oldValues[$key] = $oldModel[$key];
                    $newValues[$key] = $value;
                }
            }
        }
        if (empty($newValues))
            return false;
        return [json_encode($oldValues), json_encode($newValues)];
    }
}
