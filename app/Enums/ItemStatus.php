<?php

namespace App\Enums;

enum ItemStatus: string
{
    case Default = 'DEFAULT';
    case Delayed = 'DELAYED';
    case Critical = 'CRITICAL';
    case Test = 'TEST';
    case Done = 'DONE';
}
