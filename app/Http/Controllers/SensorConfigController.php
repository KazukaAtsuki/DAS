<?php

namespace App\Http\Controllers;

use App\Models\SensorConfig;
use App\Models\StackConfig;
use App\Models\Unit;
use App\Models\ActivityLog; // <--- PENTING: Import Model ActivityLog
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SensorConfigController extends Controller
{
    // 1. INDEX
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SensorConfig::with(['stackConfig', 'unit'])->latest()->get();

            return DataTables::of($data)
                ->addColumn('action', function($row){
                    $btn = '<a href="'.route('sensor-config.show', $row->id).'" class="btn btn-primary btn-sm me-1">Detail</a>';
                    $btn .= '<a href="'.route('sensor-config.edit', $row->id).'" class="btn btn-info btn-sm me-1 text-white">Edit</a>';
                    $btn .= '<form action="'.route('sensor-config.destroy', $row->id).'" method="POST" class="d-inline" onsubmit="return confirm(\'Are you sure you want to delete this sensor?\')">
                                '.csrf_field().'
                                '.method_field('DELETE').'
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                             </form>';
                    return $btn;
                })
                ->addColumn('stack_name', function($row){
                    return $row->stackConfig ? $row->stackConfig->stack_name : '-';
                })
                ->addColumn('unit_name', function($row){
                    return $row->unit ? $row->unit->name : '-';
                })
                ->addColumn('extra_badges', function($row){
                    $badges = '';
                    if($row->extra_parameter == 'O2')
                        $badges .= '<span class="badge bg-success me-1">O2</span>';
                    elseif($row->extra_parameter == 'Parameter RCA')
                        $badges .= '<span class="badge bg-info me-1">Parameter RCA</span>';
                    else
                        $badges .= '<span class="badge bg-secondary me-1">Non Extra Parameter</span>';

                    if($row->o2_correction == 'O2 Correction')
                        $badges .= '<span class="badge bg-success">O2 Correction</span>';
                    else
                        $badges .= '<span class="badge bg-danger">Non Correction</span>';

                    return $badges;
                })
                ->editColumn('has_parameter_reference', function($row){
                     return $row->has_parameter_reference == 'Yes'
                        ? '<span class="badge bg-success">Yes</span>'
                        : '<span class="badge bg-danger">No</span>';
                })
                ->editColumn('status', function($row){
                     return '<span class="badge bg-success">'.$row->status.'</span>';
                })
                ->rawColumns(['action', 'extra_badges', 'has_parameter_reference', 'status'])
                ->make(true);
        }

        return view('sensor_config.index');
    }

    // 2. CREATE
    public function create()
    {
        $stacks = StackConfig::all();
        $units = Unit::all();
        return view('sensor_config.create', compact('stacks', 'units'));
    }

    // 3. STORE
    public function store(Request $request)
    {
        $request->validate([
            'sensor_code' => 'required',
            'parameter_name' => 'required',
            'stack_config_id' => 'required',
            'unit_id' => 'required',
        ]);

        SensorConfig::create($request->all());

        // --- CATAT LOG CREATE ---
        ActivityLog::record('CREATE', 'Created new Sensor: ' . $request->sensor_code . ' (' . $request->parameter_name . ')');

        return redirect()->route('sensor-config.index')->with('success', 'Sensor Config created successfully!');
    }

    // 4. SHOW
    public function show($id)
    {
        $sensor = SensorConfig::with(['stackConfig', 'unit'])->findOrFail($id);
        return view('sensor_config.show', compact('sensor'));
    }

    // 5. EDIT
    public function edit($id)
    {
        $sensor = SensorConfig::findOrFail($id);
        $stacks = StackConfig::all();
        $units = Unit::all();

        return view('sensor_config.edit', compact('sensor', 'stacks', 'units'));
    }

    // 6. UPDATE
    public function update(Request $request, $id)
    {
        $request->validate([
            'sensor_code' => 'required',
            'parameter_name' => 'required',
            'stack_config_id' => 'required',
            'unit_id' => 'required',
        ]);

        $sensor = SensorConfig::findOrFail($id);
        $sensor->update($request->all());

        // --- CATAT LOG UPDATE ---
        ActivityLog::record('UPDATE', 'Updated Sensor Config: ' . $sensor->sensor_code);

        return redirect()->route('sensor-config.index')->with('success', 'Sensor Config updated successfully!');
    }

    // 7. DESTROY
    public function destroy($id)
    {
        $sensor = SensorConfig::findOrFail($id);

        // Simpan nama sebelum dihapus untuk log
        $sensorName = $sensor->sensor_code;

        $sensor->delete();

        // --- CATAT LOG DELETE ---
        ActivityLog::record('DELETE', 'Deleted Sensor Config: ' . $sensorName);

        return redirect()->route('sensor-config.index')->with('success', 'Sensor Config deleted successfully!');
    }
}