<?php

namespace App\Http\Controllers;

use App\Models\HourlyLog;
use App\Models\StackConfig;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Exports\HourlyLogExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class HourlyLogController extends Controller
{
    public function index(Request $request)
    {
        $stacks = StackConfig::all();

        if ($request->ajax()) {
            $query = HourlyLog::with('sensorConfig')->orderBy('id', 'desc');

            // 1. Filter Stack
            if ($request->has('stack_id') && $request->stack_id != '') {
                $query->where('stack_config_id', $request->stack_id);
            }

            // 2. Filter Tanggal (BARU)
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $start = $request->start_date . ' 00:00:00';
                $end   = $request->end_date . ' 23:59:59';
                $query->whereBetween('timestamp', [$start, $end]);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('timestamp', function($row){
                    return Carbon::parse($row->timestamp)->format('Y-m-d H:i');
                })
                ->addColumn('sensor_name', function($row){
                    return $row->sensorConfig->parameter_name ?? '-';
                })
                ->editColumn('measured_value', function($row){
                    return number_format($row->measured_value, 2);
                })
                ->editColumn('corrected_value', function($row){
                    return number_format($row->corrected_value, 2);
                })
                ->make(true);
        }

        return view('hourly_avg.index', compact('stacks'));
    }

    // --- UPDATE EXPORT EXCEL ---
    public function exportExcel(Request $request)
    {
        $fileName = 'Hourly_Logs_' . date('Y-m-d_H-i') . '.xlsx';
        // Kirim $request agar filter tanggal terbawa
        return Excel::download(new HourlyLogExport($request), $fileName);
    }

    // --- UPDATE EXPORT SIMPEL ---
    public function exportSimpel(Request $request)
    {
        $fileName = 'SIMPEL_Pelaporan_' . date('Y-m-d') . '.csv';
        // Kirim $request agar filter tanggal terbawa
        return Excel::download(new HourlyLogExport($request), $fileName, \Maatwebsite\Excel\Excel::CSV);
    }
}