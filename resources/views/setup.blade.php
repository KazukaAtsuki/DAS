<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Logger | DAS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --das-teal: #009688; --das-bg: #f8fafc; }
        body { background-color: var(--das-bg); font-family: 'Plus Jakarta Sans', sans-serif; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .setup-card { background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); width: 100%; max-width: 450px; text-align: center; }
        .btn-das { background: var(--das-teal); color: white; border: none; padding: 12px; border-radius: 12px; width: 100%; font-weight: 700; margin-top: 15px; }
        .btn-das:hover { background: #00796b; color: white; }
    </style>
</head>
<body>
    <div class="setup-card">
        <div class="mb-4"><i class="ti ti-settings" style="font-size: 50px; color: var(--das-teal);"></i></div>
        <h4 class="fw-bold">Initial System Setup</h4>
        <p class="text-muted small">Daftarkan Logger ID Anda ke Dashboard Pusat untuk memulai.</p>

        <form action="{{ route('setup.process') }}" method="POST">
            @csrf
            <div class="text-start mb-3">
                <label class="small fw-bold">Logger ID</label>
                <input type="text" name="logger_id" class="form-control" placeholder="Contoh: testing-001" required>
            </div>
            <div class="text-start mb-3">
                <label class="small fw-bold">Logger Name</label>
                <input type="text" name="logger_name" class="form-control" placeholder="Contoh: Sensor Unit 1" required>
            </div>
            <div class="text-start mb-4">
                <label class="small fw-bold">Admin Email</label>
                <input type="email" name="user_email" class="form-control" placeholder="email-admin@company.com" required>
            </div>
            <button type="submit" class="btn-das">Kirim Permintaan Aktivasi</button>
        </form>
    </div>
</body>
</html>