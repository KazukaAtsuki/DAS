<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Hash; // <--- PENTING: Import Model ActivityLog

class AuthController extends Controller
{
    // 1. Tampilkan Halaman Login
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    // 2. Proses Login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // --- CATAT LOG LOGIN ---
            ActivityLog::record('LOGIN', 'User has logged in to the system.');

            return redirect()->intended('/dashboard')->with('success', 'Login Berhasil! Selamat Datang.');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // 3. Proses Logout
   // app/Http/Controllers/AuthController.php

public function logout(Request $request)
{
    // 1. Ambil ID Logger dari session sebelum logout
    $loggerId = session('setup_logger_id');

    if ($loggerId) {
        try {
            // 2. Tembak Python API untuk menghapus data di pusat (Port 8000)
            \Illuminate\Support\Facades\Http::withHeaders([
                'X-API-KEY' => env('PYTHON_API_KEY')
            ])->delete(env('PYTHON_API_URL') . "/loggers/" . $loggerId);

            // 3. Hapus file gembok lokal agar mesin adam_reader juga berhenti
            $path = storage_path('app/expired.txt');
            if (\Illuminate\Support\Facades\File::exists($path)) {
                \Illuminate\Support\Facades\File::delete($path);
            }

            // 4. Hapus session ID
            session()->forget('setup_logger_id');

        } catch (\Exception $e) {
            // Jika gagal konek ke python, abaikan agar logout tetap berjalan
        }
    }

    // Prosedur Logout Standar Laravel
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login')->with('success', 'Sesi dan Lisensi telah dihapus. Silahkan login kembali.');
}

    // ... code login logout sebelumnya ...

    // 4. Halaman Profil Saya
    public function profile()
    {
        return view('auth.profile');
    }

    // 5. Update Profil (Ganti Nama/Password)
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|min:6|confirmed', // Confirmed butuh input password_confirmation
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        // Jika password diisi, update password
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        // Catat Log
        ActivityLog::record('UPDATE', 'User updated their own profile.');

        return back()->with('success', 'Profile updated successfully!');
    }

     // 6. Halaman Security
     public function security()
     {
         return view('auth.security');
     }

     // 7. Proses Ganti Password
    public function updatePassword(Request $request)
     {
         $request->validate([
             'current_password' => 'required',
             'password' => 'required|min:6|confirmed', // password_confirmation wajib ada di form
         ]);

         // Cek apakah password lama benar
         if (!Hash::check($request->current_password, Auth::user()->password)) {
             return back()->withErrors(['current_password' => 'Current password does not match our records.']);
         }

         // Update Password Baru
         Auth::user()->update([
             'password' => Hash::make($request->password)
         ]);

         // Catat Log
         ActivityLog::record('UPDATE', 'User changed their password via Security Settings.');

         return back()->with('success', 'Password changed successfully!');
     }

}