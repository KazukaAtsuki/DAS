<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil data logs dari database, urutkan dari terbaru
        $logs = ActivityLog::with('user')->latest()->paginate(15);

        // 2. Kirim variabel $logs ke View
        return view('activity_logs.index', compact('logs'));
    }
}