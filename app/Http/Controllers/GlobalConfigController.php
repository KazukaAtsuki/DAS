<?php

namespace App\Http\Controllers;

use App\Models\GlobalConfig;
use Illuminate\Http\Request;

class GlobalConfigController extends Controller
{
    // Tampilkan Halaman Config
    public function index()
    {
        // UBAH BAGIAN INI:
        // Jangan cuma ::first(), tapi pakai ::firstOrCreate()
        // Artinya: "Ambil data pertama, kalau GAK ADA, tolong buatkan data baru"

        $config = GlobalConfig::firstOrCreate(
            ['id' => 1], // Cari ID 1
            [
                // Data Default jika database kosong
                'das_unit_name' => 'Trusur DAS V3',
                'server_host' => '127.0.0.1',
                'api_endpoint' => 'http://127.0.0.1/api/val',
                'server_api_key' => null,
            ]
        );

        return view('system.global_config', compact('config'));
    }

    // Simpan Perubahan
    public function update(Request $request)
    {
        $request->validate([
            'das_unit_name' => 'required',
            'server_host' => 'required',
            'api_endpoint' => 'required',
        ]);

        $config = GlobalConfig::first();

        // Data yang mau diupdate
        $data = [
            'das_unit_name' => $request->das_unit_name,
            'server_host' => $request->server_host,
            'api_endpoint' => $request->api_endpoint,
        ];

        // Logika khusus API Key:
        // Kalau user isi kolom password, kita update.
        // Kalau kosong, biarkan password lama.
        if ($request->filled('server_api_key')) {
            $data['server_api_key'] = $request->server_api_key;
        }

        $config->update($data);

        return redirect()->back()->with('success', 'Configuration updated successfully!');
    }
}