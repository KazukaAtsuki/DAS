<?php

namespace App\Http\Controllers;

use App\Models\RcaLog;
use App\Models\StackConfig;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Exports\RcaLogExport;        // <--- TAMBAHAN 1
use Maatwebsite\Excel\Facades\Excel; // <--- TAMBAHAN 2

class RcaLogController extends Controller
{
    // ... (fungsi index biarkan saja) ...
    public function index(Request $request)
    {
        // ... (kode lama) ...
        $stacks = StackConfig::all();
        if ($request->ajax()) {
            $query = RcaLog::with('sensorConfig')->orderBy('id', 'desc');
            if ($request->has('stack_id') && $request->stack_id != '') {
                $query->where('stack_config_id', $request->stack_id);
            }
            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('timestamp', function($row){ return \Carbon\Carbon::parse($row->timestamp)->format('Y-m-d H:i:s'); })
                ->addColumn('sensor_name', function($row){ return $row->sensorConfig->parameter_name ?? '-'; })
                ->editColumn('measured_value', function($row){ return number_format($row->measured_value, 2); })
                ->editColumn('corrected_o2', function($row){ return number_format($row->corrected_o2, 2); })
                ->editColumn('raw_value', function($row){ return number_format($row->raw_value, 2); })
                ->make(true);
        }
        return view('rca_logs.index', compact('stacks'));
    }

    // --- FUNGSI EXPORT BARU ---
    public function exportExcel(Request $request)
    {
        $stackId = $request->query('stack_id');
        $fileName = 'RCA_Records_' . date('Y-m-d_H-i') . '.xlsx';

        return Excel::download(new RcaLogExport($stackId), $fileName);
    }
}