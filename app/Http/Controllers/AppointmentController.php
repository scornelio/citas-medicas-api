<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;

/**
 * @OA\Info(
 *     title="API de Gestión de Citas Médicas",
 *     version="1.0",
 *     description="API para gestionar citas médicas, permitiendo crear, listar, actualizar y eliminar citas."
 * )
 * 
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Servidor local"
 * )
 *
 * @OA\Tag(
 *     name="Appointments",
 *     description="API Endpoints for Managing Appointments"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="api_key",
 *     type="apiKey",
 *     in="header",
 *     name="Authorization",
 *     description="API key for authorization"
 * )
 */
class AppointmentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/appointments",
     *     summary="Obtener lista de citas",
     *     tags={"Appointments"},
     *     @OA\Response(
     *         response=200,
     *         description="Citas encontradas",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Appointment")),
     *             @OA\Property(property="message", type="string", example="Citas encontradas")
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No hay citas registradas",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="null"),
     *             @OA\Property(property="message", type="string", example="No hay citas registradas")
     *         )
     *     ),
     *     security={
     *         {"api_key": {}}
     *     }
     * )
     */
    public function index()
    {
        $appointments = Appointment::all();
        if(count($appointments) > 0) {
            $data = [
                'data' => $appointments,
                'message' => 'Citas encontradas'
            ];
            return response()->json($data, 200);
        }

        $data = [
            'data' => null,
            'message' => 'No hay citas registradas'
        ];
        return response()->json($data, 204);
    }

    /**
     * @OA\Get(
     *     path="/api/appointments/{id}",
     *     summary="Obtener detalles de una cita",
     *     tags={"Appointments"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la cita",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cita encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", ref="#/components/schemas/Appointment"),
     *             @OA\Property(property="message", type="string", example="Cita encontrada")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cita no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="null"),
     *             @OA\Property(property="message", type="string", example="Cita no encontrada")
     *         )
     *     ),
     *     security={
     *         {"api_key": {}}
     *     }
     * )
     */
    public function show($id)
    {
        $appointment = Appointment::find($id);
        if ($appointment) {
            $data = [
                'data' => $appointment,
                'message' => 'Cita encontrada',
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                'data' => null,
                'message' => 'Cita no encontrada',
            ];
            return response()->json($data, 404);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/appointments",
     *     summary="Crear una nueva cita",
     *     tags={"Appointments"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Appointment")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Creado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", ref="#/components/schemas/Appointment"),
     *             @OA\Property(property="message", type="string", example="Creado correctamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al crear",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="null"),
     *             @OA\Property(property="message", type="string", example="Error al crear")
     *         )
     *     ),
     *     security={
     *         {"api_key": {}}
     *     }
     * )
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'patient_name' => 'required|string|max:255',
            'doctor_name' => 'required|string|max:255',
            'appointment_date' => 'required|date',
            'status' => 'required|string|in:scheduled,completed,cancelled',
        ]);

        $appointment = Appointment::create($validatedData);
        if ($appointment) {
            $data = [
                'data' => $appointment,
                'message' => 'Creado correctamente',
            ];
            return response()->json($data, 201);
        } else {
            $data = [
                'data' => null,
                'message' => 'Error al crear',
            ];
            return response()->json($data, 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/appointments/{id}",
     *     summary="Actualizar una cita existente",
     *     tags={"Appointments"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la cita a actualizar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Appointment")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Actualizado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", ref="#/components/schemas/Appointment"),
     *             @OA\Property(property="message", type="string", example="Actualizado correctamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cita no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="null"),
     *             @OA\Property(property="message", type="string", example="Cita no encontrada")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al actualizar",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="null"),
     *             @OA\Property(property="message", type="string", example="Error al actualizar")
     *         )
     *     ),
     *     security={
     *         {"api_key": {}}
     *     }
     * )
     */
    public function update(Request $request, $id)
    {
        $appointment = Appointment::find($id);
        if (!$appointment) {
            $data = [
                'data' => null,
                'message' => 'Cita no encontrada',
            ];
            return response()->json($data, 404);
        }

        $validatedData = $request->validate([
            'patient_name' => 'sometimes|required|string|max:255',
            'doctor_name' => 'sometimes|required|string|max:255',
            'appointment_date' => 'sometimes|required|date',
            'status' => 'sometimes|required|string|in:scheduled,completed,cancelled',
        ]);

        $appointment->update($validatedData);
        if ($appointment) {
            $data = [
                'data' => $appointment,
                'message' => 'Actualizado correctamente',
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                'data' => null,
                'message' => 'Error al actualizar',
            ];
            return response()->json($data, 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/appointments/{id}",
     *     summary="Eliminar una cita",
     *     tags={"Appointments"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la cita a eliminar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Eliminado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="null"),
     *             @OA\Property(property="message", type="string", example="Eliminado correctamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cita no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="null"),
     *             @OA\Property(property="message", type="string", example="Cita no encontrada")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al eliminar",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="null"),
     *             @OA\Property(property="message", type="string", example="Error al eliminar")
     *         )
     *     ),
     *     security={
     *         {"api_key": {}}
     *     }
     * )
     */
    public function destroy($id)
    {
        $appointment = Appointment::find($id);
        if (!$appointment) {
            $data = [
                'data' => null,
                'message' => 'Cita no encontrada',
            ];
            return response()->json($data, 404);
        }

        $appointment->delete();
        if($appointment) {
            $data = [
                'data' => null,
                'message' => 'Eliminado correctamente',
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                'data' => null,
                'message' => 'Error al eliminar',
            ];
            return response()->json($data, 500);
        }
    }
}
