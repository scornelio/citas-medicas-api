<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
/**
 * @OA\Schema(
 *     schema="Appointment",
 *     type="object",
 *     title="Appointment",
 *     description="Modelo de una cita",
 *     required={"patient_name", "doctor_name", "appointment_date", "status"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID de la cita"
 *     ),
 *     @OA\Property(
 *         property="patient_name",
 *         type="string",
 *         description="Nombre del paciente"
 *     ),
 *     @OA\Property(
 *         property="doctor_name",
 *         type="string",
 *         description="Nombre del doctor"
 *     ),
 *     @OA\Property(
 *         property="appointment_date",
 *         type="string",
 *         format="date-time",
 *         description="Fecha de la cita"
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         description="Estado de la cita",
 *         enum={"scheduled", "completed", "cancelled"}
 *     )
 * )
 */
class AppointmentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'patient_name' => $this->patient_name,
            'doctor_name' => $this->doctor_name,
            'appointment_date' => $this->appointment_date,
            'status' => $this->status,
        ];
    }
}