<?php

namespace App\Http\Controllers;

use App\Models\DasLog;
use App\Models\StackConfig;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Exports\DasLogExport;           // <--- JANGAN LUPA IMPORT INI
use Maatwebsite\Excel\Facades\Excel;    // <--- JANGAN LUPA IMPORT INI

class DasLogController extends Controller
{
    public function index(Request $request)
    {
        // ... (Kode index biarkan saja seperti sebelumnya) ...
        $stacks = StackConfig::all();

        if ($request->ajax()) {
            $query = DasLog::with('sensorConfig')->orderBy('id', 'desc');

            // 1. FILTER STACK (Existing)
            if ($request->has('stack_id') && $request->stack_id != '') {
                $query->where('stack_config_id', $request->stack_id);
            }

            // 2. FILTER TANGGAL (BARU)
            if ($request->filled('start_date') && $request->filled('end_date')) {
                // Tambahkan jam 00:00:00 dan 23:59:59 agar seharian penuh terambil
                $start = $request->start_date . ' 00:00:00';
                $end   = $request->end_date . ' 23:59:59';

                $query->whereBetween('timestamp', [$start, $end]);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('timestamp', function($row){
                    return \Carbon\Carbon::parse($row->timestamp)->format('Y-m-d H:i:s');
                })
                ->addColumn('sensor_name', function($row){
                    return $row->sensorConfig->parameter_name ?? '-';
                })
                ->editColumn('measured_value', function($row){
                    return number_format($row->measured_value, 2);
                })
                ->editColumn('raw_value', function($row){
                    return number_format($row->raw_value, 2);
                })
                ->editColumn('status_sent_dis', function($row){
                    if($row->status_sent_dis == 'Sent')
                        return '<span class="badge bg-success">Sent</span>';
                    elseif($row->status_sent_dis == 'Pending')
                        return '<span class="badge bg-warning">Pending</span>';
                    else
                        return '<span class="badge bg-danger">Failed</span>';
                })
                ->rawColumns(['status_sent_dis'])
                ->make(true);
        }

        return view('logs.index', compact('stacks'));
    }

    // --- FUNGSI EXPORT BARU ---
   public function exportExcel(Request $request)
    {
        $fileName = 'DAS_Logs_' . date('Y-m-d_H-i-s') . '.xlsx';

        // Kirim seluruh $request (isinya stack_id, start_date, end_date)
        return Excel::download(new DasLogExport($request), $fileName);
    }
}