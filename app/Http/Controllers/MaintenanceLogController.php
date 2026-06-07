<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

class MaintenanceLogController extends Controller
{
    #[OA\Get(path: '/api/maintenance-logs', summary: 'List all maintenance logs', tags: ['Maintenance'],
        responses: [new OA\Response(response: 200, description: 'List of logs')]
    )]
    public function index()
    {
        $logs = MaintenanceLog::with(['extinguisher', 'inspector'])->paginate(10);
        return response()->json($logs);
    }

    #[OA\Post(path: '/api/maintenance-logs', summary: 'Create maintenance log', tags: ['Maintenance'],
        responses: [new OA\Response(response: 201, description: 'Log created')]
    )]
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'extinguisher_id' => 'required|exists:extinguishers,id',
            'inspector_id'    => 'required|exists:users,id',
            'action_taken'    => 'required|string',
            'conditions'      => 'required|string',
            'date_of_action'  => 'required|date',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $log = MaintenanceLog::create($request->all());
        return response()->json(['message' => 'Maintenance log created successfully', 'data' => $log], 201);
    }

    #[OA\Get(path: '/api/maintenance-logs/{id}', summary: 'Get log by ID', tags: ['Maintenance'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [new OA\Response(response: 200, description: 'Log details')]
    )]
    public function show($id)
    {
        $log = MaintenanceLog::with(['extinguisher', 'inspector'])->find($id);
        if (!$log) {
            return response()->json(['error' => 'Log not found'], 404);
        }
        return response()->json($log);
    }

    #[OA\Put(path: '/api/maintenance-logs/{id}', summary: 'Update log', tags: ['Maintenance'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [new OA\Response(response: 200, description: 'Log updated')]
    )]
    public function update(Request $request, $id)
    {
        $log = MaintenanceLog::find($id);
        if (!$log) {
            return response()->json(['error' => 'Log not found'], 404);
        }
        $log->update($request->all());
        return response()->json(['message' => 'Log updated successfully', 'data' => $log]);
    }

    #[OA\Delete(path: '/api/maintenance-logs/{id}', summary: 'Delete log', tags: ['Maintenance'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [new OA\Response(response: 200, description: 'Log deleted')]
    )]
    public function destroy($id)
    {
        $log = MaintenanceLog::find($id);
        if (!$log) {
            return response()->json(['error' => 'Log not found'], 404);
        }
        $log->delete();
        return response()->json(['message' => 'Log deleted successfully']);
    }
}