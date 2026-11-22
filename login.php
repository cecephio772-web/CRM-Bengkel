<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - CRM Bengkel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #0f172a; color: #f8fafc; height: 100vh; display: flex; align-items: center; }
        .card-custom { background-color: #1e293b; border: 1px solid #334155; }
    </style>
</head>
<body>
    <div class="container" style="max-width: 400px;">
        <div class="card card-custom p-4 shadow-lg text-center">
            <h3 class="fw-bold mb-1">⚙️ CRM BENGKEL</h3>
            <p class="text-secondary small mb-4">Login Mahasiswa C (Sistem CRM)</p>
            
            <form id="form-login">
                <div class="mb-3 text-start">
                    <label class="form-label small text-secondary">Username</label>
                    <input type="text" id="username" class="form-control bg-dark text-light border-secondary" placeholder="admin" required>
                </div>
                <div class="mb-4 text-start">
                    <label class="form-label small text-secondary">Password</label>
                    <input type="password" id="password" class="form-control bg-dark text-light border-secondary" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-bold py-2">MASUK APLIKASI</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('form-login').addEventListener('submit', function(e) {
            e.preventDefault();
            // Simpan status session login sederhana di LocalStorage
            localStorage.setItem('isLoggedIn', 'true');
            window.location.href = 'index.php';
        });
    </script>
</body>
</html>