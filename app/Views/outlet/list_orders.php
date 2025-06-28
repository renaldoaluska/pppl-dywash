<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Pesanan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        /* CSS LAMA ANDA (TIDAK DIUBAH) */
        body { font-family: 'Poppins', sans-serif; background: linear-gradient(to bottom, #eef2f9, #ffffff); margin: 0; padding: 20px; min-height: 100vh; }
        .container { max-width: 1100px; margin: 20px auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        h1 { color: #2c3e50; }
        .header-nav a { text-decoration: none; color: #007bff; margin-left: 15px; font-weight: 500; }
        .header-nav a:hover { text-decoration: underline; }
        .card { background-color: #fff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05); padding: 25px; margin-top: 25px; }
        .card h2 { margin-top: 0; margin-bottom: 20px; color: #34495e; font-weight: 600; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 15px; text-align: left; vertical-align: middle; border-bottom: 1px solid #ecf0f1; }
        thead th { border-bottom: 2px solid #bdc3c7; color: #7f8c8d; font-size: 14px; font-weight: 600; text-transform: uppercase; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover { background-color: #f8f9fa; }
        td strong { font-weight: 500; }
        .status-badge { padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: capitalize; }
        .status-diterima { color: #0056b3; background-color: #cfe2ff; }
        .status-diproses { color: #856404; background-color: #fff3cd; }
        .status-selesai { color: #0f5132; background-color: #d1e7dd; }
        .status-diulas { color: #41464b; background-color: #e2e3e5; }
        .status-ditolak { color: #721c24; background-color: #f8d7da; }
        select, button { padding: 8px 12px; border-radius: 8px; border: 1px solid #ced4da; font-size: 14px; font-family: 'Poppins', sans-serif; }
        select:focus { outline: none; border-color: #80bdff; box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25); }
        button { cursor: pointer; background-color: #007bff; color: white; border: none; font-weight: 500; transition: background-color 0.2s; }
        button:hover { background-color: #0056b3; }
        .update-form { display: flex; gap: 8px; align-items: center; }

        /* =================================
           CSS BARU UNTUK POPUP/MODAL
        ================================= */
        .modal {
            display: none; /* Disembunyikan secara default */
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5); /* Latar belakang gelap transparan */
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            width: 90%;
            max-width: 400px;
            text-align: center;
            animation: slide-down 0.3s ease-out;
        }
        @keyframes slide-down {
            from { transform: translateY(-30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .modal-content p {
            font-size: 18px;
            color: #34495e;
            margin: 0 0 25px 0;
        }
        .modal-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        .modal-buttons button {
            padding: 10px 25px;
            font-weight: 600;
        }
        #confirmBtnTidak {
            background-color: #6c757d; /* Warna abu-abu untuk "Tidak" */
        }
        #confirmBtnTidak:hover {
            background-color: #5a6268;
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Manajemen Pesanan</h1>
            <nav class="header-nav">
                <a href="/dashboard">Kembali ke Dashboard</a>
                <a href="/logout">Logout</a>
            </nav>
        </div>

        <div class="card">
            <h2>Pesanan Aktif</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID Pesanan</th>
                        <th>Outlet</th>
                        <th>Customer</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pending_orders)): ?>
                        <?php foreach ($pending_orders as $order): ?>
                            <tr>
                                <td><strong>#<?= $order['order_id'] ?></strong></td>
                                <td><?= esc($order['outlet_name']) ?></td>
                                <td><?= esc($order['customer_name']) ?></td>
                                <td><?= date('d M Y', strtotime($order['order_date'])) ?></td>
                                <td>
                                    <span class="status-badge status-<?= esc($order['status']) ?>">
                                        <?= esc($order['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <form action="/outlet/orders/update/<?= $order['order_id'] ?>" method="post" class="update-form">
                                        <?= csrf_field() ?>
                                        <select name="status">
                                            <option value="diproses" <?= $order['status'] == 'diproses' ? 'selected' : '' ?>>Diproses</option>
                                            <option value="selesai" <?= $order['status'] == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                                            <option value="ditolak" <?= $order['status'] == 'ditolak' ? 'selected' : '' ?>>Tolak</option>
                                        </select>
                                        <button type="button" class="update-btn">Update</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px;">Tidak ada pesanan aktif yang perlu diproses saat ini. 🎉</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="card">
            <h2>Riwayat Pesanan</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID Pesanan</th>
                        <th>Outlet</th>
                        <th>Customer</th>
                        <th>Tanggal</th>
                        <th>Status Final</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($history_orders)): ?>
                        <?php foreach ($history_orders as $order): ?>
                            <tr>
                                <td><strong>#<?= $order['order_id'] ?></strong></td>
                                <td><?= esc($order['outlet_name']) ?></td>
                                <td><?= esc($order['customer_name']) ?></td>
                                <td><?= date('d M Y', strtotime($order['order_date'])) ?></td>
                                <td>
                                     <span class="status-badge status-<?= esc($order['status']) ?>">
                                        <?= esc($order['status']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px;">Belum ada riwayat pesanan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="confirmationModal" class="modal">
        <div class="modal-content">
            <p>Apakah Anda yakin ingin mengupdate status pesanan ini?</p>
            <div class="modal-buttons">
                <button id="confirmBtnTidak">Tidak</button>
                <button id="confirmBtnYa">Ya, Update</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('confirmationModal');
            const confirmBtnYa = document.getElementById('confirmBtnYa');
            const confirmBtnTidak = document.getElementById('confirmBtnTidak');
            const updateButtons = document.querySelectorAll('.update-btn');

            let formToSubmit = null;

            // Tambahkan event listener ke setiap tombol "Update" di tabel
            updateButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    // Temukan form terdekat dari tombol yang diklik
                    formToSubmit = event.target.closest('form');
                    // Tampilkan modal
                    modal.style.display = 'flex';
                });
            });

            // Jika tombol "Ya, Update" di modal diklik
            confirmBtnYa.addEventListener('click', function() {
                if (formToSubmit) {
                    // Kirim form yang sudah kita simpan tadi
                    formToSubmit.submit();
                }
            });

            // Jika tombol "Tidak" di modal diklik
            confirmBtnTidak.addEventListener('click', function() {
                // Sembunyikan modal
                modal.style.display = 'none';
                formToSubmit = null; // Reset form yang akan disubmit
            });

            // Sembunyikan modal jika user mengklik di luar area konten modal
            window.addEventListener('click', function(event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                    formToSubmit = null;
                }
            });
        });
    </script>

</body>
</html>