<?php

namespace App\Enums;

enum DeviceStatus: string
{
    case ON = 'ON';
    case OFF = 'OFF';
    case IN_USE = 'IN_USE';
}
