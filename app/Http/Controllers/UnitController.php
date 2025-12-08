<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UnitController extends Controller
{
    // 1. INDEX (Tampilkan Tabel)
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Unit::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    // Tombol Edit
                    $btn = '<a href="'.route('units.edit', $row->id).'" class="btn btn-primary btn-sm me-1"><i class="ti ti-pencil"></i> Edit</a>';

                    // Tombol Delete
                    $btn .= '<form action="'.route('units.destroy', $row->id).'" method="POST" class="d-inline" onsubmit="return confirm(\'Are you sure you want to delete this unit?\')">
                                '.csrf_field().'
                                '.method_field('DELETE').'
                                <button type="submit" class="btn btn-danger btn-sm"><i class="ti ti-trash"></i> Delete</button>
                             </form>';
                    return $btn;
                })
                ->editColumn('created_at', function($row){
                    return $row->created_at ? $row->created_at->format('d M Y') : '-';
                })
                ->editColumn('updated_at', function($row){
                    return $row->updated_at ? $row->updated_at->format('d M Y') : '-';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('units.index');
    }

    // 2. CREATE (Halaman Tambah)
    public function create()
    {
        return view('units.create');
    }

    // 3. STORE (Simpan Data Baru)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        Unit::create($request->all());

        return redirect()->route('units.index')->with('success', 'Unit created successfully!');
    }

    // 4. EDIT (Halaman Edit - INI YANG KETINGGALAN TADI)
    public function edit($id)
    {
        // Cari data berdasarkan ID, kalau ga ketemu error 404
        $unit = Unit::findOrFail($id);

        // Kirim variabel $unit ke view
        return view('units.edit', compact('unit'));
    }

    // 5. UPDATE (Simpan Perubahan)
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $unit = Unit::findOrFail($id);
        $unit->update($request->all());

        return redirect()->route('units.index')->with('success', 'Unit updated successfully!');
    }

    // 6. DESTROY (Hapus Data)
    public function destroy($id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete();

        return redirect()->route('units.index')->with('success', 'Unit deleted successfully!');
    }
}