<?php

namespace App\Http\Controllers;

use App\Models\Extinguisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

class ExtinguisherController extends Controller
{
    #[OA\Get(path: '/api/extinguishers', summary: 'List all extinguishers', tags: ['Extinguishers'],
        responses: [new OA\Response(response: 200, description: 'List of extinguishers')]
    )]
    public function index()
    {
        $extinguishers = Extinguisher::paginate(10);
        return response()->json($extinguishers);
    }

    #[OA\Post(path: '/api/extinguishers', summary: 'Register new extinguisher', tags: ['Extinguishers'],
        responses: [new OA\Response(response: 201, description: 'Extinguisher created')]
    )]
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'serial_number'     => 'required|unique:extinguishers',
            'location'          => 'required|string',
            'type'              => 'required|in:Water,CO2,Foam,Dry Chemical',
            'size'              => 'required|in:2.5 lbs,5 lbs,9 lbs,12 lbs',
            'installation_date' => 'required|date',
            'expiry_date'       => 'required|date|after:installation_date',
            'status'            => 'in:active,expired,maintenance',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $extinguisher = Extinguisher::create($request->all());
        return response()->json(['message' => 'Extinguisher registered successfully', 'data' => $extinguisher], 201);
    }

    #[OA\Get(path: '/api/extinguishers/{id}', summary: 'Get extinguisher by ID', tags: ['Extinguishers'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [new OA\Response(response: 200, description: 'Extinguisher details')]
    )]
    public function show($id)
    {
        $extinguisher = Extinguisher::find($id);
        if (!$extinguisher) {
            return response()->json(['error' => 'Extinguisher not found'], 404);
        }
        return response()->json($extinguisher);
    }

    #[OA\Put(path: '/api/extinguishers/{id}', summary: 'Update extinguisher', tags: ['Extinguishers'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [new OA\Response(response: 200, description: 'Extinguisher updated')]
    )]
    public function update(Request $request, $id)
    {
        $extinguisher = Extinguisher::find($id);
        if (!$extinguisher) {
            return response()->json(['error' => 'Extinguisher not found'], 404);
        }
        $extinguisher->update($request->all());
        return response()->json(['message' => 'Extinguisher updated successfully', 'data' => $extinguisher]);
    }

    #[OA\Delete(path: '/api/extinguishers/{id}', summary: 'Delete extinguisher', tags: ['Extinguishers'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [new OA\Response(response: 200, description: 'Extinguisher deleted')]
    )]
    public function destroy($id)
    {
        $extinguisher = Extinguisher::find($id);
        if (!$extinguisher) {
            return response()->json(['error' => 'Extinguisher not found'], 404);
        }
        $extinguisher->delete();
        return response()->json(['message' => 'Extinguisher deleted successfully']);
    }
}