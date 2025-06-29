<?= $this->extend('customer/layout') ?>

<?= $this->section('title') ?>
Daftar Alamat
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-2xl mx-auto">

    <div class="bg-white rounded-xl p-6 shadow space-y-4">

        <!-- Tombol kembali -->
        <div>
            <a href="/customer/profil" class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 transition-colors text-slate-700 text-sm font-semibold py-2 px-4 rounded-lg no-underline">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Kembali</span>
            </a>
        </div>

        <h1 class="text-xl font-bold">Daftar Alamat</h1>

        <?php if (empty($addresses)): ?>
    <p class="text-slate-600">Kamu belum memiliki alamat tersimpan.</p>
<?php else: ?>
    <div class="space-y-4">
<?php foreach ($addresses as $address): ?>
    <div class="p-4 border rounded bg-white shadow space-y-2">
        <div>
            <p class="font-semibold"><?= esc($address['label']) ?><?= $address['is_primary'] ? ' (Utama)' : '' ?></p>
        </div>

        <p><?= esc($address['recipient_name']) ?> | <?= esc($address['phone_number']) ?></p>
        <p class="text-slate-600"><?= esc($address['address_detail']) ?></p>

        <div class="flex flex-col sm:flex-row sm:flex-wrap sm:items-center gap-2 pt-2 border-t">
            <a href="/customer/profil/alamat/edit/<?= $address['address_id'] ?>"
               class="w-full sm:w-auto text-center inline-flex justify-center px-3 py-2 rounded-md text-sm font-medium bg-blue-50 text-blue-700 hover:bg-blue-100 transition">
                Edit
            </a>

            <?php if (!$address['is_primary']): ?>
                <form action="/customer/profil/alamat/set-primary/<?= $address['address_id'] ?>" method="post" class="w-full sm:w-auto">
                    <?= csrf_field() ?>
                    <button type="submit"
                        class="w-full sm:w-auto text-center inline-flex justify-center px-3 py-2 rounded-md text-sm font-medium bg-green-50 text-green-700 hover:bg-green-100 transition">
                        Jadikan Utama
                    </button>
                </form>

                <button type="button"
                    class="w-full sm:w-auto text-center inline-flex justify-center px-3 py-2 rounded-md text-sm font-medium bg-red-50 text-red-700 hover:bg-red-100 transition"
                    data-delete-id="<?= $address['address_id'] ?>">
                    Hapus
                </button>
            <?php else: ?>
                <button type="button"
                    class="w-full sm:w-auto text-center inline-flex justify-center px-3 py-2 rounded-md text-sm font-medium bg-gray-100 text-gray-400 cursor-not-allowed"
                    disabled>
                    Jadikan Utama
                </button>

                <button type="button"
                    class="w-full sm:w-auto text-center inline-flex justify-center px-3 py-2 rounded-md text-sm font-medium bg-gray-100 text-gray-400 cursor-not-allowed"
                    disabled>
                    Hapus
                </button>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>

    </div>
<?php endif; ?>


        <!-- Tombol tambah di bawah daftar -->
        <div>
            <a href="/customer/profil/alamat/tambah" class="w-full inline-flex justify-center items-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm font-bold py-3 px-4 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Tambah Alamat</span>
            </a>
        </div>

    </div>

</div>

<!-- ================================
     MODAL KONFIRMASI HAPUS
     ================================ -->
<div id="delete-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
  <div class="bg-white rounded-lg max-w-sm w-full p-6">
    <h2 class="text-lg font-bold mb-4">Hapus Alamat</h2>
    <p class="text-slate-700 mb-6">Apakah kamu yakin ingin menghapus alamat ini?</p>
    <form id="delete-form" method="post">
      <?= csrf_field() ?>
      <div class="flex justify-end gap-3">
        <button type="button" id="cancel-delete" class="bg-gray-300 hover:bg-gray-400 text-slate-800 py-2 px-4 rounded">Batal</button>
        <button type="button" id="confirm-delete-btn" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded">Hapus</button>
      </div>
    </form>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    // Variabel untuk menyimpan ID alamat yang akan dihapus
    let addressIdToDelete = null;

    // Ambil elemen-elemen penting
    const deleteModal = document.getElementById('delete-modal');
    const cancelDeleteBtn = document.getElementById('cancel-delete');
    const confirmDeleteBtn = document.getElementById('confirm-delete-btn');
    const csrfInput = document.querySelector('#delete-form input[name="<?= csrf_token() ?>"]');

    // 1. Tambahkan event listener ke semua tombol "Hapus" di daftar alamat
    document.querySelectorAll('button[data-delete-id]').forEach(button => {
        button.addEventListener('click', () => {
            // Simpan ID dari tombol yang diklik
            addressIdToDelete = button.getAttribute('data-delete-id');
            // Tampilkan modal
            deleteModal.classList.remove('hidden');
        });
    });

    // 2. Tambahkan event listener ke tombol "Batal" di dalam modal
    cancelDeleteBtn.addEventListener('click', () => {
        // Sembunyikan modal dan reset ID
        deleteModal.classList.add('hidden');
        addressIdToDelete = null;
    });

    // 3. Tambahkan event listener ke tombol "Hapus" yang BARU di dalam modal
    confirmDeleteBtn.addEventListener('click', () => {
        // Jika tidak ada ID yang tersimpan, jangan lakukan apa-apa
        if (!addressIdToDelete) return;

        // Ambil data CSRF
        const csrfName = csrfInput.getAttribute('name');
        const csrfHash = csrfInput.getAttribute('value');
        
        // Siapkan data untuk dikirim
        const formData = new URLSearchParams();
        formData.append(csrfName, csrfHash);
        
        // Ubah tombol untuk menunjukkan proses loading (opsional tapi bagus)
        confirmDeleteBtn.disabled = true;
        confirmDeleteBtn.innerHTML = 'Menghapus...';

        // Kirim permintaan hapus menggunakan Fetch API
        fetch(`/customer/profil/alamat/delete/${addressIdToDelete}`, {
            method: 'POST',
            headers: {
                // Header ini penting untuk CodeIgniter tahu ini request AJAX
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json().catch(() => ({}))) // Coba parse JSON, atau return objek kosong
        .then(data => {
            // Setelah selesai, refresh halaman untuk melihat hasilnya
            // CI4 redirect akan mengirim kita ke halaman daftar alamat lagi
            window.location.href = '/customer/profil/alamat';
        })
        .catch(error => {
            // Jika ada error jaringan
            console.error('Error:', error);
            alert('Terjadi kesalahan jaringan. Gagal menghapus alamat.');
            // Kembalikan tombol ke keadaan semula
            confirmDeleteBtn.disabled = false;
            confirmDeleteBtn.innerHTML = 'Hapus';
        });
    });
</script>

<?php if (session()->has('success')): ?>
<div id="toast-success" class="fixed bottom-4 right-4 bg-green-600 text-white px-4 py-3 rounded shadow transition transform translate-y-0 opacity-100">
    <?= session('success') ?>
</div>

<script>
    setTimeout(() => {
        const toast = document.getElementById('toast-success');
        if (toast) {
            toast.classList.add('opacity-0', 'translate-y-4');
            setTimeout(() => toast.remove(), 300);
        }
    }, 3000);
</script>
<?php endif; ?>

<?php if (session()->has('error')): ?>
<div id="toast-error" class="fixed bottom-4 right-4 z-50 bg-red-600 text-white px-4 py-3 rounded-lg shadow-lg" role="alert">
    <strong>Error:</strong> <?= session('error') ?>
</div>

<script>
    setTimeout(() => {
        const toast = document.getElementById('toast-error');
        if (toast) {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 500);
        }
    }, 5000); // Pesan akan hilang setelah 5 detik
</script>
<?php endif; ?>
<?= $this->endSection() ?>
