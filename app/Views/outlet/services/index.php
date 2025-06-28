<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Layanan Outlet - Dywash</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-slate-100">

    <div class="container max-w-7xl mx-auto my-8 px-4">
        <header class="flex flex-col sm:flex-row justify-between sm:items-center mb-6 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-800">Kelola Layanan Anda</h1>
                <p class="mt-1 text-slate-500">Atur semua layanan yang tersedia di outlet Anda.</p>
            </div>
            <div class="w-full sm:w-auto flex flex-col sm:flex-row gap-2">
                                        <a href="/dashboard" class="w-full sm:w-auto text-center text-white bg-gray-600 hover:bg-gray-700 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors duration-200">
            Kembali ke Dashboard
        </a>
                <a href="/outlet/services/create" class="w-full text-center text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors duration-200">
                    Tambah Layanan Baru
                </a>

            </div>
        </header>

        <!-- ============================================== -->
        <!-- ==      KONTEN UTAMA (DESKTOP & MOBILE)     == -->
        <!-- ============================================== -->
        <div class="bg-white rounded-xl shadow-lg p-6 mt-6">
            <h2 class="text-xl font-bold text-slate-700 mb-5">Daftar Layanan</h2>

            <!-- Tampilan Desktop: Tabel -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-slate-500 uppercase bg-slate-50">
                        <tr>
                            <th class="p-4">Nama Layanan</th>
                            <th class="p-4">Harga</th>
                            <th class="p-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <?php if (!empty($services)): ?>
                            <?php foreach ($services as $service): ?>
                                <tr class="hover:bg-slate-50">
                                    <td class="p-4 font-semibold text-slate-800"><?= esc($service['name']) ?></td>
                                    <td class="p-4 text-slate-600">Rp <?= number_format($service['price'], 0, ',', '.') ?> / <?= esc($service['unit']) ?></td>
                                    <td class="p-4">
                                        <div class="flex items-center gap-4">
                                            <a href="/outlet/services/edit/<?= $service['service_id'] ?>" class="font-medium text-blue-600 hover:underline">Edit</a>
                                            <button data-url="/outlet/services/delete/<?= $service['service_id'] ?>" data-name="<?= esc($service['name']) ?>" class="delete-btn font-medium text-red-600 hover:underline">Hapus</button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="text-center p-10 text-slate-500">Anda belum memiliki layanan.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Tampilan Mobile: Kartu -->
            <div class="block md:hidden space-y-4">
                <?php if (!empty($services)): ?>
                    <?php foreach ($services as $service): ?>
                        <div class="border border-slate-200 rounded-lg p-4 shadow-sm">
                            <div class="mb-2">
                                <h3 class="font-bold text-slate-800"><?= esc($service['name']) ?></h3>
                                <p class="text-sm text-blue-600 font-semibold">Rp <?= number_format($service['price'], 0, ',', '.') ?> / <?= esc($service['unit']) ?></p>
                            </div>
                            <div class="flex items-center gap-2 mt-4 border-t border-slate-100 pt-3">
                                <a href="/outlet/services/edit/<?= $service['service_id'] ?>" class="w-full text-center bg-slate-100 text-slate-700 font-semibold py-2 px-4 rounded-lg hover:bg-slate-200 transition-colors duration-200">
                                    Edit
                                </a>
                                <button data-url="/outlet/services/delete/<?= $service['service_id'] ?>" data-name="<?= esc($service['name']) ?>" class="delete-btn w-full text-center bg-red-100 text-red-700 font-semibold py-2 px-4 rounded-lg hover:bg-red-200 transition-colors duration-200">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center p-10 text-slate-500">Anda belum memiliki layanan. Klik tombol di atas untuk memulai.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- POPUP MODAL KONFIRMASI HAPUS -->
    <div id="deleteConfirmationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-md text-center">
            <svg class="w-16 h-16 text-red-500 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
            <h3 class="text-xl font-bold text-slate-800">Anda Yakin?</h3>
            <p class="text-slate-500 mt-2 mb-6">Anda akan menghapus layanan <strong id="serviceNameToDelete" class="font-bold"></strong>. Aksi ini tidak dapat dibatalkan.</p>
            <div class="flex justify-center gap-4">
                <button id="cancelDeleteBtn" class="px-6 py-2 font-semibold text-white bg-gray-500 rounded-lg hover:bg-gray-600">Batal</button>
                <a id="confirmDeleteBtn" href="#" class="px-6 py-2 font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700">Ya, Hapus</a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('deleteConfirmationModal');
            if (modal) {
                const serviceNameToDelete = document.getElementById('serviceNameToDelete');
                const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
                const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
                const deleteButtons = document.querySelectorAll('.delete-btn');

                deleteButtons.forEach(button => {
                    button.addEventListener('click', function(event) {
                        event.preventDefault(); // Mencegah link langsung berjalan
                        const serviceName = this.dataset.name;
                        const deleteUrl = this.dataset.url;
                        
                        // Isi data ke modal
                        serviceNameToDelete.textContent = serviceName;
                        confirmDeleteBtn.href = deleteUrl;

                        // Tampilkan modal
                        modal.classList.remove('hidden');
                    });
                });

                function hideModal() {
                    modal.classList.add('hidden');
                }

                cancelDeleteBtn.addEventListener('click', hideModal);
                modal.addEventListener('click', (event) => {
                    if (event.target === modal) {
                        hideModal();
                    }
                });
            }
        });
    </script>
</body>
</html>