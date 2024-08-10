<?php

namespace App\Repositories;

use Illuminate\Support\Collection;

interface AppointmentRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
}