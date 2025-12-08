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
        // Jika request datang dari AJAX DataTables
        if ($request->ajax()) {
            $data = StackConfig::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn() // Untuk nomor urut (DT_RowIndex)
                ->addColumn('action', function($row){
                    // Tombol Edit & Delete
                    $btn = '<a href="javascript:void(0)" class="btn btn-primary btn-sm me-1">Edit</a>';
                    $btn .= '<a href="javascript:void(0)" class="btn btn-danger btn-sm">Delete</a>';
                    return $btn;
                })
                ->editColumn('oxygen_reference', function($row){
                    return $row->oxygen_reference ? $row->oxygen_reference . '%' : 'null %';
                })
                ->editColumn('status', function($row){
                    // Ubah tampilan status jadi Badge warna
                    if($row->status == 'Active'){
                        return '<span class="badge bg-success">Active</span>';
                    } else {
                        return '<span class="badge bg-danger">Inactive</span>';
                    }
                })
                ->editColumn('created_at', function($row){
                    return $row->created_at->format('d M Y');
                })
                ->editColumn('updated_at', function($row){
                    return $row->updated_at->format('d M Y');
                })
                ->rawColumns(['action', 'status']) // Render HTML untuk kolom ini
                ->make(true);
        }

        return view('stack_config.index');
    }

    // 2. Fungsi CREATE (Menampilkan Form)
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
                         ->with('success', 'Data berhasil ditambahkan!');
    }
}