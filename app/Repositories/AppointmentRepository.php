<?php

namespace App\Repositories;

use App\Models\Appointment;
use Illuminate\Support\Collection;

class AppointmentRepository implements AppointmentRepositoryInterface
{
    public function all(): Collection
    {
        return Appointment::all();
    }

    public function find(int $id)
    {
        return Appointment::find($id);
    }

    public function create(array $data)
    {
        return Appointment::create($data);
    }

    public function update(int $id, array $data)
    {
        $appointment = Appointment::find($id);
        if ($appointment) {
            $appointment->update($data);
            return $appointment;
        }
        return null;
    }

    public function delete(int $id)
    {
        $appointment = Appointment::find($id);
        if ($appointment) {
            return $appointment->delete();
        }
        return false;
    }
}
