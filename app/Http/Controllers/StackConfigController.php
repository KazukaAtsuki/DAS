<?php

namespace App\Http\Controllers;

use App\Models\StackConfig;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StackConfigController extends Controller
{
    // 1. Fungsi INDEX (Menampilkan Tabel)
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = StackConfig::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    // TOMBOL EDIT - Mengarah ke halaman Edit
                    $btn = '<a href="'.route('stack-config.edit', $row->id).'" class="btn btn-primary btn-sm me-1"><i class="ti ti-pencil"></i> Edit</a>';

                    // TOMBOL DELETE - Menggunakan Form agar aman (Security Standar Laravel)
                    $btn .= '<form action="'.route('stack-config.destroy', $row->id).'" method="POST" class="d-inline" onsubmit="return confirm(\'Apakah Anda yakin ingin menghapus stack ini?\')">
                                '.csrf_field().'
                                '.method_field('DELETE').'
                                <button type="submit" class="btn btn-danger btn-sm"><i class="ti ti-trash"></i> Delete</button>
                             </form>';
                    return $btn;
                })
                ->editColumn('oxygen_reference', function($row){
                    return $row->oxygen_reference ? $row->oxygen_reference . '%' : 'null %';
                })
                ->editColumn('status', function($row){
                    $badgeClass = ($row->status == 'Active') ? 'bg-success' : 'bg-danger';
                    return '<span class="badge '.$badgeClass.'">'.$row->status.'</span>';
                })
                ->editColumn('created_at', function($row){
                    return $row->created_at->format('d M Y');
                })
                ->editColumn('updated_at', function($row){
                    return $row->updated_at->format('d M Y');
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('stack_config.index');
    }

    // 2. Fungsi CREATE (Menampilkan Form Tambah)
    public function create()
    {
        return view('stack_config.create');
    }

    // 3. Fungsi STORE (Simpan Data Baru)
    public function store(Request $request)
    {
        $request->validate([
            'stack_name' => 'required',
            'status' => 'required',
        ]);

        StackConfig::create($request->all());

        return redirect()->route('stack-config.index')
                         ->with('success', 'Stack berhasil ditambahkan!');
    }

    // 4. Fungsi EDIT (Menampilkan Form Edit)
    public function edit($id)
    {
        $stack = StackConfig::findOrFail($id);
        return view('stack_config.edit', compact('stack'));
    }

    // 5. Fungsi UPDATE (Simpan Perubahan)
    public function update(Request $request, $id)
    {
        $request->validate([
            'stack_name' => 'required',
            'status' => 'required',
        ]);

        $stack = StackConfig::findOrFail($id);
        $stack->update($request->all());

        return redirect()->route('stack-config.index')
                         ->with('success', 'Stack berhasil diperbarui!');
    }

    // 6. Fungsi DESTROY (Hapus Data)
    public function destroy($id)
    {
        $stack = StackConfig::findOrFail($id);
        $stack->delete();

        return redirect()->route('stack-config.index')
                         ->with('success', 'Stack berhasil dihapus!');
    }
}