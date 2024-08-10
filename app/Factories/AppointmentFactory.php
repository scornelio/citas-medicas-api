<?php

namespace App\Factories;

use App\Repositories\AppointmentRepository;
use App\Repositories\AppointmentRepositoryInterface;

class AppointmentFactory
{
    public static function make(): AppointmentRepositoryInterface
    {
        return new AppointmentRepository();
    }
}