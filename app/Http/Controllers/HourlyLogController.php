<?php

namespace App\Http\Controllers;

use App\Models\HourlyLog;
use App\Models\StackConfig;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Exports\HourlyLogExport; // <--- Import ini
use Maatwebsite\Excel\Facades\Excel; // <--- Import ini

class HourlyLogController extends Controller
{
    public function index(Request $request)
    {
        // ... (kode index yang lama biarkan saja) ...
        $stacks = StackConfig::all();

        if ($request->ajax()) {
            $query = HourlyLog::with('sensorConfig')->orderBy('id', 'desc');

            if ($request->has('stack_id') && $request->stack_id != '') {
                $query->where('stack_config_id', $request->stack_id);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('timestamp', function($row){
                    return \Carbon\Carbon::parse($row->timestamp)->format('Y-m-d H:i');
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

        return view('hourly_logs.index', compact('stacks'));
    }

    // --- FUNGSI EXPORT EXCEL ---
    public function exportExcel(Request $request)
    {
        $stackId = $request->query('stack_id');
        $fileName = 'Hourly_Logs_' . date('Y-m-d_H-i') . '.xlsx';

        return Excel::download(new HourlyLogExport($stackId), $fileName);
    }

    // --- FUNGSI EXPORT SIMPEL (CSV Format KLHK) ---
    public function exportSimpel(Request $request)
    {
        $stackId = $request->query('stack_id');
        $fileName = 'SIMPEL_Pelaporan_' . date('Y-m-d') . '.csv';

        // CSV biasanya formatnya lebih sederhana
        return Excel::download(new HourlyLogExport($stackId), $fileName, \Maatwebsite\Excel\Excel::CSV);
    }
}