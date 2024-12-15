<?php

namespace App\Enums;

enum UserProjectRole : string
{
    case ADMIN = 'ADMIN';
    case EDITOR = 'EDITOR';
    case SUBSCRIBER = 'SUBSCRIBER';
}
