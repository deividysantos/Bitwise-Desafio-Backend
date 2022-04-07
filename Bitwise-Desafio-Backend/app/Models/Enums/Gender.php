<?php

namespace App\Models\Enums;

enum Gender: string
{
    case Male = 'male';
    case Female ='female';
    case NotSpecified = 'not specified';
}
