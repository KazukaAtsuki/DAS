<?php

namespace App\Http\Controllers;

use App\Models\DasLog;
use App\Models\StackConfig;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DasLogController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua stack untuk dropdown filter di pojok kanan
        $stacks = StackConfig::all();

        if ($request->ajax()) {
            // Query dasar: ambil data log beserta nama sensornya
            $query = DasLog::with('sensorConfig')->orderBy('id', 'desc');

            // FILTER: Jika user memilih Stack tertentu
            if ($request->has('stack_id') && $request->stack_id != '') {
                $query->where('stack_config_id', $request->stack_id);
            }

            return DataTables::of($query)
                ->addIndexColumn() // Untuk kolom #ID (No urut)
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
}