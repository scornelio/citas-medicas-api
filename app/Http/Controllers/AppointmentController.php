<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Factories\AppointmentFactory;

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
    protected $appointmentFactory;

    public function __construct()
    {
        $this->appointmentFactory = AppointmentFactory::make();
    }

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
        try {
            $appointments = $this->appointmentFactory->all();
            if (count($appointments) > 0) {
                $data = [
                    'data' => $appointments,
                    'message' => 'Citas encontradas'
                ];
                return response()->json($data, 200);
            }

            return response()->noContent();
        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('Error fetching appointments: '.$e->getMessage());
            return response()->json(['error' => 'Ocurrió un error en Base de Datos'], 500);
        } catch (\Exception $e) {
            \Log::error('Error fetching appointments: '.$e->getMessage());
            return response()->json(['error' => 'Ocurrió un error'], 500);
        }
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
        try{
            $appointment = $this->appointmentFactory->find($id);
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
        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('Error appointments: '.$e->getMessage());
            return response()->json(['error' => 'Ocurrió un error en Base de Datos'], 500);
        } catch (\Exception $e) {
            \Log::error('Error appointments: '.$e->getMessage());
            return response()->json(['error' => 'Ocurrió un error'], 500);
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
        try{
            $validatedData = $request->validate([
                'patient_name' => 'required|string|max:255',
                'doctor_name' => 'required|string|max:255',
                'appointment_date' => 'required|date',
                'status' => 'required|string|in:scheduled,completed,cancelled',
            ]);
    
            $appointment = $this->appointmentFactory->create($validatedData);
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
        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('Error appointments: '.$e->getMessage());
            return response()->json(['error' => 'Ocurrió un error en Base de Datos'], 500);
        } catch (\Exception $e) {
            \Log::error('Error appointments: '.$e->getMessage());
            return response()->json(['error' => 'Ocurrió un error'], 500);
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
        try{
            $validatedData = $request->validate([
                'patient_name' => 'sometimes|required|string|max:255',
                'doctor_name' => 'sometimes|required|string|max:255',
                'appointment_date' => 'sometimes|required|date',
                'status' => 'sometimes|required|string|in:scheduled,completed,cancelled',
            ]);
    
            $appointment = $this->appointmentFactory->update($id, $validatedData);
            if ($appointment) {
                $data = [
                    'data' => $appointment,
                    'message' => 'Actualizado correctamente',
                ];
                return response()->json($data, 200);
            } else {
                $data = [
                    'data' => null,
                    'message' => 'Cita no encontrada',
                ];
                return response()->json($data, 404);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('Error appointments: '.$e->getMessage());
            return response()->json(['error' => 'Ocurrió un error en Base de Datos'], 500);
        } catch (\Exception $e) {
            \Log::error('Error appointments: '.$e->getMessage());
            return response()->json(['error' => 'Ocurrió un error'], 500);
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
        try{
            $deleted = $this->appointmentFactory->delete($id);
            if ($deleted) {
                $data = [
                    'data' => null,
                    'message' => 'Eliminado correctamente',
                ];
                return response()->json($data, 200);
            } else {
                $data = [
                    'data' => null,
                    'message' => 'Cita no encontrada',
                ];
                return response()->json($data, 404);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('Error appointments: '.$e->getMessage());
            return response()->json(['error' => 'Ocurrió un error en Base de Datos'], 500);
        } catch (\Exception $e) {
            \Log::error('Error appointments: '.$e->getMessage());
            return response()->json(['error' => 'Ocurrió un error'], 500);
        } 
        
    }
}
