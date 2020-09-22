<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $logicClass = null;

    public function __construct()
    {
        if($this->logicClass !== null && class_exists($this->logicClass)) {
            $this->logicClass = new $this->logicClass();
        }
    }
}
