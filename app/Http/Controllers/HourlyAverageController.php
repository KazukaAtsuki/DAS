<?php

namespace App\Http\Controllers;

use App\Models\HourlyAverage;
use App\Models\StackConfig;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class HourlyAverageController extends Controller
{
    public function index(Request $request)
    {
        $stacks = StackConfig::all();

        if ($request->ajax()) {
            $query = HourlyAverage::with('sensorConfig')->latest('timestamp');

            // Filter Stack
            if ($request->has('stack_id') && $request->stack_id != '') {
                $query->where('stack_config_id', $request->stack_id);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('timestamp', function($row){
                    // Format Jam Bulat (Contoh: 2025-12-03 14:00)
                    return Carbon::parse($row->timestamp)->format('Y-m-d H:00');
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