<?php

namespace App\Enums;

enum ExpenseType: string
{
    case MAINTENANCE = 'maintenance';
    case RENT = 'rent';
    case SALARY = 'salary';
    case UTILITIES = 'utilities';
    case MARKETING = 'marketing';
    case HARDWARE = 'hardware';
    case OTHER = 'other';
}
