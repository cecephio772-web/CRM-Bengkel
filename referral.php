<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Referral - CRM Bengkel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #0f172a; color: #f8fafc; }
        .navbar-custom { background-color: #1e293b; border-bottom: 2px solid #334155; }
        .card-custom { background-color: #1e293b; border: 1px solid #334155; color: #ffffff; }
        .table-dark-custom { --bs-table-bg: #1e293b; --bs-table-color: #e2e8f0; border-color: #334155; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom mb-4 shadow">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">⚙️ CRM BENGKEL ENGINE</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php">Pelanggan</a>
                <a class="nav-link" href="poin-promo.php">Poin & Promo</a>
                <a class="nav-link active fw-bold" href="referral.php">Referral</a>
                <a class="nav-link" href="kirim-wa.php">Reminder WA</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h4 class="fw-bold mb-3">PROGRAM REFERRAL PELANGGAN</h4>
        <div class="card card-custom shadow-sm">
            <div class="card-body p-0">
                <table class="table table-dark-custom mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3">Kode Referral</th>
                            <th>Pemilik (Pengajak)</th>
                            <th>Bonus Poin</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="referral-table">
                        <tr><td colspan="4" class="text-center py-3 text-muted">Memuat kode referral...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        fetch('http://localhost/bengkel-api/api/customers.php')
            .then(res => res.json())
            .then(result => {
                if(result.status) {
                    let rows = '';
                    result.data.forEach(c => {
                        let kode = "REF-" + c.nama.substring(0,3).toUpperCase() + c.id;
                        rows += `
                            <tr>
                                <td class="ps-3"><code class="text-warning fw-bold">${kode}</code></td>
                                <td class="fw-bold">${c.nama}</td>
                                <td><span class="badge bg-info">+10 Poin / Pengguna</span></td>
                                <td><button class="btn btn-sm btn-outline-light" onclick="navigator.clipboard.writeText('${kode}'); alert('Kode disalin!');">Salin Kode</button></td>
                            </tr>
                        `;
                    });
                    document.getElementById('referral-table').innerHTML = rows;
                }
            });
    </script>
</body>
</html>