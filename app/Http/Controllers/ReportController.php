<?php

namespace App\Http\Controllers;

use App\Models\Extinguisher;
use App\Models\Inspection;
use App\Models\MaintenanceLog;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    #[OA\Get(path: '/api/reports/general', summary: 'General report', tags: ['Reports'],
        responses: [new OA\Response(response: 200, description: 'General statistics')]
    )]
    public function generalReport()
    {
        $today = now()->toDateString();
        $monthStart = now()->startOfMonth()->toDateString();
        $yearStart = now()->startOfYear()->toDateString();

        return response()->json([
            'total_extinguishers' => [
                'daily'   => Extinguisher::whereDate('created_at', $today)->count(),
                'monthly' => Extinguisher::whereDate('created_at', '>=', $monthStart)->count(),
                'yearly'  => Extinguisher::whereDate('created_at', '>=', $yearStart)->count(),
                'all'     => Extinguisher::count(),
            ],
            'expired_extinguishers'     => Extinguisher::where('status', 'expired')->count(),
            'active_extinguishers'      => Extinguisher::where('status', 'active')->count(),
            'maintenance_extinguishers' => Extinguisher::where('status', 'maintenance')->count(),
            'inspection_status' => [
                'scheduled'  => Inspection::where('status', 'scheduled')->count(),
                'completed'  => Inspection::where('status', 'completed')->count(),
                'cancelled'  => Inspection::where('status', 'cancelled')->count(),
            ],
            'total_maintenance_logs' => MaintenanceLog::count(),
        ]);
    }

    #[OA\Get(path: '/api/reports/maintenance-history', summary: 'Maintenance history', tags: ['Reports'],
        responses: [new OA\Response(response: 200, description: 'Maintenance history')]
    )]
    public function maintenanceHistory()
    {
        $logs = MaintenanceLog::with(['extinguisher', 'inspector'])
            ->orderBy('date_of_action', 'desc')
            ->paginate(10);
        return response()->json($logs);
    }

    #[OA\Get(path: '/api/reports/expired', summary: 'Expired extinguishers', tags: ['Reports'],
        responses: [new OA\Response(response: 200, description: 'Expired extinguishers')]
    )]
    public function expiredExtinguishers()
    {
        $expired = Extinguisher::where('status', 'expired')
            ->orWhere('expiry_date', '<', now())
            ->paginate(10);
        return response()->json($expired);
    }

    #[OA\Get(path: '/api/reports/export/csv', summary: 'Export CSV', tags: ['Reports'],
        responses: [new OA\Response(response: 200, description: 'CSV download')]
    )]
    public function exportCSV()
    {
        $extinguishers = Extinguisher::all();
        $filename = 'extinguishers_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        $callback = function() use ($extinguishers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Serial Number', 'Location', 'Type', 'Size', 'Installation Date', 'Expiry Date', 'Status']);
            foreach ($extinguishers as $e) {
                fputcsv($file, [$e->id, $e->serial_number, $e->location, $e->type, $e->size, $e->installation_date, $e->expiry_date, $e->status]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    #[OA\Get(path: '/api/reports/export/pdf', summary: 'Export PDF', tags: ['Reports'],
        responses: [new OA\Response(response: 200, description: 'PDF download')]
    )]
    public function exportPDF()
    {
        $extinguishers = Extinguisher::all();
        $pdf = Pdf::loadView('reports.extinguishers', compact('extinguishers'));
        return $pdf->download('extinguishers_' . date('Y-m-d') . '.pdf');
    }
}