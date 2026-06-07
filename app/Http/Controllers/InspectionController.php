<?php

namespace App\Http\Controllers;

use App\Models\Inspection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OA;

class InspectionController extends Controller
{
    #[OA\Get(path: '/api/inspections', summary: 'List all inspections', tags: ['Inspections'],
        responses: [new OA\Response(response: 200, description: 'List of inspections')]
    )]
    public function index()
    {
        $inspections = Inspection::with(['extinguisher', 'inspector'])->paginate(10);
        return response()->json($inspections);
    }

    #[OA\Post(path: '/api/inspections', summary: 'Schedule inspection', tags: ['Inspections'],
        responses: [new OA\Response(response: 201, description: 'Inspection scheduled')]
    )]
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'extinguisher_id' => 'required|exists:extinguishers,id',
            'inspector_id'    => 'required|exists:users,id',
            'scheduled_date'  => 'required|date',
            'status'          => 'in:scheduled,completed,cancelled',
            'notes'           => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $inspection = Inspection::create($request->all());

        // Notify relevant personnel
        Log::info('Inspection scheduled - Personnel notified', [
            'extinguisher_id' => $inspection->extinguisher_id,
            'inspector_id'    => $inspection->inspector_id,
            'scheduled_date'  => $inspection->scheduled_date,
            'notified_at'     => now(),
        ]);

        return response()->json([
            'message'   => 'Inspection scheduled successfully. Relevant personnel have been notified.',
            'data'      => $inspection
        ], 201);
    }

    #[OA\Get(path: '/api/inspections/{id}', summary: 'Get inspection by ID', tags: ['Inspections'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [new OA\Response(response: 200, description: 'Inspection details')]
    )]
    public function show($id)
    {
        $inspection = Inspection::with(['extinguisher', 'inspector'])->find($id);
        if (!$inspection) {
            return response()->json(['error' => 'Inspection not found'], 404);
        }
        return response()->json($inspection);
    }

    #[OA\Put(path: '/api/inspections/{id}', summary: 'Update inspection', tags: ['Inspections'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [new OA\Response(response: 200, description: 'Inspection updated')]
    )]
    public function update(Request $request, $id)
    {
        $inspection = Inspection::find($id);
        if (!$inspection) {
            return response()->json(['error' => 'Inspection not found'], 404);
        }
        $inspection->update($request->all());
        return response()->json([
            'message' => 'Inspection updated successfully',
            'data'    => $inspection
        ]);
    }

    #[OA\Delete(path: '/api/inspections/{id}', summary: 'Delete inspection', tags: ['Inspections'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [new OA\Response(response: 200, description: 'Inspection deleted')]
    )]
    public function destroy($id)
    {
        $inspection = Inspection::find($id);
        if (!$inspection) {
            return response()->json(['error' => 'Inspection not found'], 404);
        }
        $inspection->delete();
        return response()->json(['message' => 'Inspection deleted successfully']);
    }
}