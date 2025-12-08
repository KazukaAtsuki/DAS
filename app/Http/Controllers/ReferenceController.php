<?php

namespace App\Http\Controllers;

use App\Models\Reference;
use App\Models\SensorConfig;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ReferenceController extends Controller
{
    // 1. INDEX
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Load relasi sensor -> stack
            $data = Reference::with(['sensorConfig.stackConfig'])->latest()->get();

            return DataTables::of($data)
                ->addColumn('action', function($row){
                    $btn = '<a href="'.route('references.show', $row->id).'" class="btn btn-primary btn-sm me-1">Detail</a>';
                    $btn .= '<a href="'.route('references.edit', $row->id).'" class="btn btn-info btn-sm me-1 text-white">Edit</a>';
                    $btn .= '<form action="'.route('references.destroy', $row->id).'" method="POST" class="d-inline" onsubmit="return confirm(\'Delete this reference?\')">
                                '.csrf_field().'
                                '.method_field('DELETE').'
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                             </form>';
                    return $btn;
                })
                ->addColumn('stack_name', function($row){
                    // Ambil nama Stack via Sensor
                    return $row->sensorConfig && $row->sensorConfig->stackConfig
                        ? $row->sensorConfig->stackConfig->stack_name
                        : '-';
                })
                ->addColumn('sensor_name', function($row){
                    // Ambil nama Parameter (misal: Dust)
                    return $row->sensorConfig ? $row->sensorConfig->parameter_name : '-';
                })
                ->editColumn('created_at', function($row){
                    return $row->created_at->format('d M Y');
                })
                ->editColumn('updated_at', function($row){
                    return $row->updated_at->diffForHumans();
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('references.index');
    }

    // 2. CREATE
    public function create()
    {
        // Ambil semua sensor, sertakan nama stack biar jelas di dropdown
        // Format dropdown nanti: "Stack LK - Dust"
        $sensors = SensorConfig::with('stackConfig')->where('status', 'Active')->get();
        return view('references.create', compact('sensors'));
    }

    // 3. STORE
    public function store(Request $request)
    {
        $request->validate([
            'sensor_config_id' => 'required',
            'range_start' => 'required|numeric',
            'range_end' => 'required|numeric',
            'formula' => 'required',
        ]);

        Reference::create($request->all());

        return redirect()->route('references.index')->with('success', 'Reference created successfully!');
    }

    // 4. SHOW
    public function show($id)
    {
        $reference = Reference::with(['sensorConfig.stackConfig'])->findOrFail($id);
        return view('references.show', compact('reference'));
    }

    // 5. EDIT
    public function edit($id)
    {
        $reference = Reference::findOrFail($id);
        $sensors = SensorConfig::with('stackConfig')->where('status', 'Active')->get();
        return view('references.edit', compact('reference', 'sensors'));
    }

    // 6. UPDATE
    public function update(Request $request, $id)
    {
        $request->validate([
            'sensor_config_id' => 'required',
            'range_start' => 'required|numeric',
            'range_end' => 'required|numeric',
            'formula' => 'required',
        ]);

        $reference = Reference::findOrFail($id);
        $reference->update($request->all());

        return redirect()->route('references.index')->with('success', 'Reference updated successfully!');
    }

    // 7. DESTROY
    public function destroy($id)
    {
        $ref = Reference::findOrFail($id);
        $ref->delete();
        return redirect()->route('references.index')->with('success', 'Reference deleted successfully!');
    }
}