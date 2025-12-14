<?php

namespace App\Http\Controllers;

use App\Models\HourlyLog;
use App\Models\StackConfig;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class HourlyLogController extends Controller
{
    public function index(Request $request)
    {
        $stacks = StackConfig::all();

        if ($request->ajax()) {
            // Ambil data HourlyLog
            $query = HourlyLog::with('sensorConfig')->orderBy('id', 'desc');

            // Filter Stack
            if ($request->has('stack_id') && $request->stack_id != '') {
                $query->where('stack_config_id', $request->stack_id);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('timestamp', function($row){
                    return \Carbon\Carbon::parse($row->timestamp)->format('Y-m-d H:i'); // Format jam
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
}