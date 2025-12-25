<?php

namespace App\Classes\Enums;

enum CheckoutOptions: string
{
    case PENDING = "pending";
    case COMPLETED = "completed";
    case FAILED = "failed";
}
