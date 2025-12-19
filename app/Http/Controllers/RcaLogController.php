<?php

namespace App\Http\Controllers;

use App\Models\RcaLog;
use App\Models\StackConfig;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Exports\RcaLogExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class RcaLogController extends Controller
{
    public function index(Request $request)
    {
        $stacks = StackConfig::all();

        if ($request->ajax()) {
            $query = RcaLog::with('sensorConfig')->orderBy('id', 'desc');

            // 1. FILTER STACK (Logika Lama)
            if ($request->has('stack_id') && $request->stack_id != '') {
                $query->where('stack_config_id', $request->stack_id);
            }

            // 2. FILTER TANGGAL (TAMBAHAN BARU)
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $start = $request->start_date . ' 00:00:00';
                $end   = $request->end_date . ' 23:59:59';

                $query->whereBetween('timestamp', [$start, $end]);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('timestamp', function($row){
                    return Carbon::parse($row->timestamp)->format('Y-m-d H:i:s');
                })
                ->addColumn('sensor_name', function($row){
                    return $row->sensorConfig->parameter_name ?? '-';
                })
                ->editColumn('measured_value', function($row){
                    return number_format($row->measured_value, 2);
                })
                ->editColumn('corrected_o2', function($row){
                    return number_format($row->corrected_o2, 2);
                })
                ->editColumn('raw_value', function($row){
                    return number_format($row->raw_value, 2);
                })
                ->make(true);
        }

        return view('rca_logs.index', compact('stacks'));
    }

    // --- FUNGSI EXPORT (UPDATED) ---
    public function exportExcel(Request $request)
    {
        $fileName = 'RCA_Records_' . date('Y-m-d_H-i') . '.xlsx';

        // Kirim seluruh $request ke Export Class agar filter tanggal terbawa
        return Excel::download(new RcaLogExport($request), $fileName);
    }
}