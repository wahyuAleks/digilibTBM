<?php
use yii\helpers\Url;
use yii\helpers\Html;

// Cek apakah user adalah admin
$isAdmin = !Yii::$app->user->isGuest && Yii::$app->user->identity->tipe_user === 'admin';
?>
<style>
    .card-shadow {
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    }
    .admin-table {
        table-layout: fixed;
        width: 100%;
    }
</style>

<div class="p-6 max-w-7xl mx-auto">

    <?php if (\Yii::$app->session->hasFlash('success')): ?>
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800">
            <?= \Yii::$app->session->getFlash('success') ?>
        </div>
    <?php endif; ?>

    <?php if (\Yii::$app->session->hasFlash('error')): ?>
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800">
            <?= \Yii::$app->session->getFlash('error') ?>
        </div>
    <?php endif; ?>

    <main class="bg-white p-6 rounded-xl main-card card-shadow">

        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Kelola Data Anggota</h2>
                <p class="text-gray-500 text-sm">Tambah, edit, atau hapus data anggota perpustakaan</p>
            </div>
            <?php if ($isAdmin): ?>
            <button onclick="openModal()" class="flex items-center bg-black text-white px-4 py-2 rounded-lg font-medium transition duration-150 hover:bg-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Anggota
            </button>
            <?php endif; ?>
        </div>

        <div class="mb-6 flex items-center space-x-4">
            <div class="relative w-full md:w-1/3">
                <input type="text" id="searchAnggota" placeholder="Cari berdasarkan nama, email, atau nomor telepon..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>

        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="admin-table text-sm text-gray-600">
                <thead class="bg-gray-50 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-600">
                    <tr>
                        <th class="p-3 text-left w-[200px]">Nama</th>
                        <th class="p-3 text-left w-[200px]">Email</th>
                        <th class="p-3 text-left w-[150px]">No. Telepon</th>
                        <th class="p-3 text-left w-[250px]">Alamat</th>
                        <th class="p-3 text-left w-[120px]">Tanggal Bergabung</th>
                        <th class="p-3 text-left w-[100px]">Status</th>
                        <?php if ($isAdmin): ?>
                        <th class="p-3 text-center w-[120px]">Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php
                    $searchModel = new \app\models\AnggotaSearch();
                    $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
                    $models = $dataProvider->getModels();
                    ?>
                    <?php if (empty($models)): ?>
                    <tr>
                        <td colspan="<?= $isAdmin ? '7' : '6' ?>" class="p-8 text-center text-gray-500">
                            Tidak ada data anggota
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($models as $anggota): ?>
                        <tr class="bg-white hover:bg-gray-50">
                            <td class="p-3">
                                <?php
                                // Ambil nama dari relasi user, bukan dari anggota
                                $nama = 'N/A';
                                if ($anggota->user && isset($anggota->user->nama)) {
                                    $nama = $anggota->user->nama;
                                } elseif ($anggota->user && isset($anggota->user->email)) {
                                    $nama = $anggota->user->email;
                                }
                                ?>
                                <p class="font-medium text-gray-800"><?= Html::encode($nama) ?></p>
                            </td>
                            <td class="p-3">
                                <?php
                                $email = 'N/A';
                                if ($anggota->user && isset($anggota->user->email)) {
                                    $email = $anggota->user->email;
                                }
                                ?>
                                <?= Html::encode($email) ?>
                            </td>
                            <td class="p-3">
                                <?php
                                // Cek apakah ada kolom telepon di anggota atau user
                                $telepon = 'N/A';
                                if (isset($anggota->telepon)) {
                                    $telepon = $anggota->telepon;
                                } elseif ($anggota->user && isset($anggota->user->telepon)) {
                                    $telepon = $anggota->user->telepon;
                                }
                                ?>
                                <?= Html::encode($telepon) ?>
                            </td>
                            <td class="p-3">
                                <?php
                                // Cek apakah ada kolom alamat
                                $alamat = 'N/A';
                                if (isset($anggota->alamat)) {
                                    $alamat = $anggota->alamat;
                                }
                                ?>
                                <?= Html::encode($alamat) ?>
                            </td>
                            <td class="p-3">
                                <?php
                                // Cek apakah ada kolom tanggal bergabung
                                $tanggalBergabung = 'N/A';
                                if (isset($anggota->tanggalBergabung)) {
                                    $tanggalBergabung = date('Y-m-d', strtotime($anggota->tanggalBergabung));
                                } elseif (isset($anggota->created_at)) {
                                    $tanggalBergabung = date('Y-m-d', strtotime($anggota->created_at));
                                }
                                ?>
                                <?= Html::encode($tanggalBergabung) ?>
                            </td>
                            <td class="p-3">
                                <?php
                                $status = 'Aktif';
                                if ($anggota->user && isset($anggota->user->status)) {
                                    $status = $anggota->user->status === 'aktif' ? 'Aktif' : 'Nonaktif';
                                }
                                $statusClass = $status === 'Aktif' ? 'bg-black text-white' : 'bg-gray-200 text-gray-700';
                                ?>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full <?= $statusClass ?>">
                                    <?= Html::encode($status) ?>
                                </span>
                            </td>
                            <?php if ($isAdmin): ?>
                            <td class="p-3 text-center space-x-2">
                                <a href="<?= Url::to(['/anggota/view', 'id' => $anggota->anggotaID]) ?>" class="text-yellow-600 hover:text-yellow-800 inline-block" title="Lihat Detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </a>
                                <a href="<?= Url::to(['/anggota/update', 'id' => $anggota->anggotaID]) ?>" class="text-blue-600 hover:text-blue-800 inline-block" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.586a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <?php
                                // Ambil nama anggota untuk konfirmasi
                                $namaAnggota = 'anggota ini';
                                if ($anggota->user && isset($anggota->user->nama)) {
                                    $namaAnggota = Html::encode($anggota->user->nama);
                                } elseif ($anggota->user && isset($anggota->user->email)) {
                                    $namaAnggota = Html::encode($anggota->user->email);
                                }
                                ?>
                                <?= Html::a(
                                    '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.86 10.933a2 2 0 01-1.993 1.776H7.853a2 2 0 01-1.993-1.776L5 7m14 0H5m11 0V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
                                    </svg>',
                                    ['/anggota/delete', 'id' => $anggota->anggotaID],
                                    [
                                        'class' => 'text-red-600 hover:text-red-800 inline-block',
                                        'data' => [
                                            'method' => 'post',
                                            'confirm' => 'Apakah Anda yakin ingin menghapus anggota "' . $namaAnggota . '"? Tindakan ini tidak dapat dibatalkan.',
                                        ],
                                        'title' => 'Hapus',
                                    ]
                                ) ?>
                            </td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<?php if ($isAdmin): ?>
<!-- Modal Tambah Anggota -->
<div id="modalTambahAnggota" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <!-- Background overlay -->
    <div class="fixed inset-0 bg-gray-500 bg-opacity-50 transition-opacity" onclick="closeModal()"></div>

    <!-- Modal panel - Centered box -->
    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto transform transition-all duration-300 ease-in-out">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Tambah Anggota Baru</h3>
                    <p class="text-sm text-gray-500 mt-1">Masukkan informasi anggota yang akan ditambahkan</p>
                </div>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <?php
            $userList = \yii\helpers\ArrayHelper::map(\app\models\User::find()->where(['tipe_user' => 'anggota'])->all(), 'userid', 'email');
            ?>
            <form action="<?= Url::to(['anggota/create']) ?>" method="post" class="space-y-4">
                <?= Html::hiddenInput(\Yii::$app->request->csrfParam, \Yii::$app->request->csrfToken) ?>

            <div class="space-y-4 max-h-[60vh] overflow-y-auto pr-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="Anggota[nama]" placeholder="Masukkan nama lengkap" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" placeholder="email@example.com" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <p class="text-xs text-gray-500 mt-1">Email akan digunakan untuk login</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                    <input type="text" name="telepon" placeholder="08123456789" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <textarea name="alamat" rows="3" placeholder="Masukkan alamat lengkap" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih User (jika sudah ada)</label>
                    <select name="Anggota[userID]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">Pilih User (opsional - akan dibuat otomatis jika kosong)</option>
                        <?php foreach ($userList as $userId => $userEmail): ?>
                            <option value="<?= $userId ?>"><?= Html::encode($userEmail) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Jika tidak dipilih, user baru akan dibuat otomatis dari email</p>
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeModal()" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <?= Html::submitButton('Simpan', [
                    'class' => 'px-6 py-2 bg-black text-white rounded-lg font-medium hover:bg-gray-800 transition-colors'
                ]) ?>
            </div>

            </form>
        </div>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('modalTambahAnggota').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('modalTambahAnggota').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal on Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeModal();
    }
});

// Live search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchAnggota');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            const tableRows = document.querySelectorAll('.admin-table tbody tr');
            
            tableRows.forEach(function(row) {
                // Skip the "no data" row
                if (row.querySelector('td[colspan]')) {
                    return;
                }
                
                // Get text content from nama, email, and telepon columns (columns 0, 1, 2)
                const nama = row.cells[0]?.textContent.toLowerCase() || '';
                const email = row.cells[1]?.textContent.toLowerCase() || '';
                const telepon = row.cells[2]?.textContent.toLowerCase() || '';
                
                // Show row if search term matches any of the three columns
                if (nama.includes(searchTerm) || email.includes(searchTerm) || telepon.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});
</script>
<?php endif; ?>
