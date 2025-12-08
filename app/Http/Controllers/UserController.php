<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // 1. INDEX (Tampilkan Tabel)
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    // Tombol Action dengan Link Asli + Form Delete

                    // Link Detail
                    $btn = '<a href="'.route('users.show', $row->id).'" class="btn btn-primary btn-sm me-1">Detail</a>';

                    // Link Edit
                    $btn .= '<a href="'.route('users.edit', $row->id).'" class="btn btn-info btn-sm me-1 text-white">Edit</a>';

                    // Tombol Delete (Pakai Form agar aman & standar Laravel)
                    $btn .= '<form action="'.route('users.destroy', $row->id).'" method="POST" class="d-inline" onsubmit="return confirm(\'Apakah Anda yakin ingin menghapus user ini?\')">
                                '.csrf_field().'
                                '.method_field('DELETE').'
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                             </form>';

                    return $btn;
                })
                ->editColumn('created_at', function($row){
                    return $row->created_at->format('l, d F Y, H:i:s');
                })
                ->editColumn('updated_at', function($row){
                    return $row->updated_at->diffForHumans();
                })
                ->rawColumns(['action']) // Wajib agar HTML tombol terbaca
                ->make(true);
        }

        return view('users.index');
    }

    // 2. CREATE (Halaman Tambah)
    public function create()
    {
        return view('users.create');
    }

    // 3. STORE (Simpan Data Baru)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'role' => 'required',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil dibuat!');
    }

    // 4. SHOW (Halaman Detail)
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }

    // 5. EDIT (Halaman Edit)
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    // 6. UPDATE (Simpan Perubahan)
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            // validasi email unik kecuali punya user ini sendiri
            'email' => 'required|email|unique:users,email,'.$id,
            'role' => 'required',
        ]);

        $user = User::findOrFail($id);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        // Cek apakah password diisi? Kalau kosong, jangan diupdate.
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui!');
    }

    // 7. DESTROY (Hapus Data)
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
    }
}