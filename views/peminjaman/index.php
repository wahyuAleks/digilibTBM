<?php
/* @var $this yii\web\View */
/* @var $searchModel app\models\PeminjamanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $sedangDipinjam int */
/* @var $terlambat int */
/* @var $sudahDikembalikan int */
/* @var $activeTab string */

use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'Kelola Peminjaman';

// Ambil tab aktif dari controller
$activeTab = $activeTab ?? 'aktif';

// Ambil models dari dataProvider
$models = $dataProvider->getModels();

// Debug: Tampilkan jumlah model dan status untuk troubleshooting
// Uncomment baris di bawah ini jika perlu debug
if ($activeTab === 'menunggu') {
    // Debug untuk tab menunggu (hanya aktif di DEV)
    $allPending = \app\models\Peminjaman::find()
        ->where(['status' => 'menunggu_verifikasi_admin'])
        ->all();
    if (YII_DEBUG) {
        \Yii::info('DEBUG: Total peminjaman dengan status "menunggu_verifikasi_admin" di database: ' . count($allPending), 'peminjaman');
        \Yii::info('DEBUG: Jumlah models dari dataProvider untuk tab menunggu: ' . count($models), 'peminjaman');
        \Yii::info('DEBUG: Search model status: ' . ($searchModel->status ?? 'NULL'), 'peminjaman');
        foreach ($allPending as $p) {
            \Yii::info('DEBUG: Peminjaman ID: ' . $p->getId() . ', Status: ' . ($p->status ?? 'N/A') . ', AnggotaID: ' . ($p->anggotaID ?? 'N/A'), 'peminjaman');
        }
    }
    
    // Debug SQL query
    try {
        $sql = $dataProvider->query->createCommand()->getRawSql();
        if (YII_DEBUG) {
            \Yii::info('DEBUG: SQL Query untuk tab menunggu: ' . $sql, 'peminjaman');
        }
    } catch (\Exception $e) {
        if (YII_DEBUG) {
            \Yii::info('DEBUG: Error getting SQL: ' . $e->getMessage(), 'peminjaman');
        }
    }
}

// Flash messages
if (\Yii::$app->session->hasFlash('success')): ?>
    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800">
        <?= \Yii::$app->session->getFlash('success') ?>
    </div>
<?php endif; ?>

<?php if (\Yii::$app->session->hasFlash('error')): ?>
    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800">
        <?= \Yii::$app->session->getFlash('error') ?>
    </div>
<?php endif; ?>

<?php if (\Yii::$app->session->hasFlash('warning')): ?>
    <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg text-yellow-800">
        <?= \Yii::$app->session->getFlash('warning') ?>
    </div>
<?php endif; ?>

<?php
// Hitung pinjaman yang menunggu verifikasi
$menungguVerifikasi = \app\models\Peminjaman::find()
    ->where(['status' => 'menunggu_verifikasi_admin'])
    ->count();
?>

<?php if ($menungguVerifikasi > 0 && $activeTab !== 'menunggu'): ?>
    <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <p class="text-sm font-medium text-yellow-800">
                    Ada <strong><?= $menungguVerifikasi ?></strong> pinjaman yang menunggu verifikasi. 
                    <a href="<?= Url::to(['index', 'tab' => 'menunggu']) ?>" class="underline font-semibold hover:text-yellow-900">Klik di sini untuk melihat dan memverifikasi</a>.
                </p>
            </div>
        </div>
    </div>
<?php endif; ?>

<style>
    .card-shadow {
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    }
    .stats-card {
        background: white;
        border: 1px solid #E5E7EB;
        border-radius: 0.75rem;
        padding: 1.5rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    }
    .tab-active {
        background-color: white;
        color: #111827;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }
    .tab-inactive {
        color: #6B7280;
    }
    .tab-inactive:hover {
        color: #374151;
    }
    .admin-table {
        table-layout: fixed;
        width: 100%;
    }
</style>

<div class="p-6 max-w-7xl mx-auto">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <div class="stats-card flex flex-col justify-between h-32">
            <div class="flex justify-between items-start">
                <span class="text-sm font-medium text-gray-700">Sedang Dipinjam</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <span class="text-3xl font-semibold text-gray-900"><?= $sedangDipinjam ?></span>
                <p class="text-xs text-gray-500 mt-1">peminjaman aktif</p>
            </div>
        </div>

        <div class="stats-card flex flex-col justify-between h-32">
            <div class="flex justify-between items-start">
                <span class="text-sm font-medium text-gray-700">Terlambat</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <span class="text-3xl font-semibold text-gray-900"><?= $terlambat ?></span>
                <p class="text-xs text-gray-500 mt-1">perlu dikembalikan</p>
            </div>
        </div>

        <div class="stats-card flex flex-col justify-between h-32">
            <div class="flex justify-between items-start">
                <span class="text-sm font-medium text-gray-700">Sudah Dikembalikan</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <span class="text-3xl font-semibold text-gray-900"><?= $sudahDikembalikan ?></span>
                <p class="text-xs text-gray-500 mt-1">total riwayat</p>
            </div>
        </div>
    </div>

    <main class="bg-white p-6 rounded-xl main-card card-shadow min-h-[500px]">
        
        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Kelola Peminjaman & Pengembalian</h2>
                <p class="text-gray-500 text-sm">Monitor dan kelola peminjaman buku perpustakaan</p>
            </div>
        </div>

        <div class="mb-6">
            <?php $form = \yii\widgets\ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
                'options' => ['class' => 'relative'],
            ]); ?>
            <div class="relative">
                <span class="absolute left-0 pl-3 top-3 flex items-start pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 relative top-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" name="PeminjamanSearch[search]" value="<?= Html::encode($searchModel->search ?? '') ?>" class="w-full bg-gray-100 text-gray-700 border-transparent focus:border-gray-300 focus:bg-white focus:ring-0 rounded-lg pl-10 py-3 text-sm placeholder-gray-500" placeholder="Cari berdasarkan nama anggota atau judul buku...">
                <input type="hidden" name="tab" value="<?= Html::encode($activeTab) ?>">
            </div>
            <?php \yii\widgets\ActiveForm::end(); ?>
        </div>

        <div class="mb-6">
            <div class="bg-gray-100 p-1 rounded-lg flex font-medium text-sm">
                <a href="<?= Url::to(['index', 'tab' => 'menunggu']) ?>" class="flex-1 py-2 rounded-md transition-all text-center <?= $activeTab === 'menunggu' ? 'tab-active font-semibold' : 'tab-inactive' ?>">
                    Menunggu Verifikasi (<?= $menungguVerifikasi ?>)
                </a>
                <a href="<?= Url::to(['index', 'tab' => 'aktif']) ?>" class="flex-1 py-2 rounded-md transition-all text-center <?= $activeTab === 'aktif' ? 'tab-active font-semibold' : 'tab-inactive' ?>">
                    Aktif (<?= $sedangDipinjam ?>)
                </a>
                <a href="<?= Url::to(['index', 'tab' => 'terlambat']) ?>" class="flex-1 py-2 rounded-md transition-all text-center <?= $activeTab === 'terlambat' ? 'tab-active font-semibold' : 'tab-inactive' ?>">
                    Terlambat (<?= $terlambat ?>)
                </a>
                <a href="<?= Url::to(['index', 'tab' => 'riwayat']) ?>" class="flex-1 py-2 rounded-md transition-all text-center <?= $activeTab === 'riwayat' ? 'tab-active font-semibold' : 'tab-inactive' ?>">
                    Riwayat (<?= $sudahDikembalikan ?>)
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="admin-table w-full text-left text-sm">
                <thead class="text-gray-900 font-semibold border-b border-gray-200 bg-gray-50">
                    <tr>
                        <th class="pb-3 pl-4 pt-3">Anggota</th>
                        <th class="pb-3 pt-3">Judul Buku</th>
                        <th class="pb-3 pt-3">Tgl Pinjam</th>
                        <th class="pb-3 pt-3">Jatuh Tempo</th>
                        <th class="pb-3 pt-3">Sisa Waktu</th>
                        <th class="pb-3 pt-3">Status</th>
                        <th class="pb-3 pt-3">Denda</th>
                        <th class="pb-3 text-right pr-4 pt-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 divide-y divide-gray-100">
                    <?php 
                    // Debug: Untuk tab menunggu, cek langsung dari database jika models kosong
                    if ($activeTab === 'menunggu' && empty($models)) {
                        $directQuery = \app\models\Peminjaman::find()
                            ->where(['status' => 'menunggu_verifikasi_admin'])
                            ->with(['anggota.user'])
                            ->all();
                        if (YII_DEBUG) {
                            \Yii::info("DEBUG: Query langsung menemukan " . count($directQuery) . " peminjaman dengan status 'menunggu_verifikasi_admin'", 'peminjaman');
                        }
                        if (!empty($directQuery)) {
                            if (YII_DEBUG) {
                                \Yii::info("DEBUG: DataProvider menemukan " . count($models) . " models, tapi query langsung menemukan " . count($directQuery), 'peminjaman');
                            }
                            // Gunakan data dari query langsung sebagai fallback
                            $models = $directQuery;
                        }
                    }
                    ?>
                    <?php if (empty($models)): ?>
                    <tr>
                        <td colspan="8" class="py-8 text-center text-gray-500">
                            <?php if ($activeTab === 'menunggu'): ?>
                                Tidak ada peminjaman yang menunggu verifikasi
                                <?php 
                                // Debug info
                                $count = \app\models\Peminjaman::find()
                                    ->where(['status' => 'menunggu_verifikasi_admin'])
                                    ->count();
                                if ($count > 0): ?>
                                    <br><span class="text-xs text-red-500">(Debug: Database menunjukkan <?= $count ?> peminjaman dengan status ini. Silakan refresh halaman.)</span>
                                <?php endif; ?>
                            <?php else: ?>
                                Tidak ada data peminjaman
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($models as $peminjaman): ?>
                        <?php
                        // Ambil nama anggota
                        $anggotaNama = 'N/A';
                        if ($peminjaman->anggota && $peminjaman->anggota->user) {
                            $anggotaNama = $peminjaman->anggota->user->nama ?? $peminjaman->anggota->user->email ?? 'N/A';
                        }
                        
                        // Ambil judul buku dari item peminjaman
                        $judulBuku = [];
                        try {
                            if ($peminjaman->itemPeminjamans) {
                                foreach ($peminjaman->itemPeminjamans as $item) {
                                    if ($item && $item->buku) {
                                        $judulBuku[] = $item->buku->judul;
                                    }
                                }
                            }
                        } catch (\Exception $e) {
                            // Jika error (tabel tidak ada), set default
                            $judulBuku = [];
                        }
                        $judulBukuText = !empty($judulBuku) ? implode(', ', $judulBuku) : 'N/A';
                        
                        // Ambil tanggal pinjam
                        $tanggalPinjam = 'N/A';
                        if ($peminjaman->hasAttribute('tanggalPinjam') && $peminjaman->tanggalPinjam) {
                            $tanggalPinjam = date('Y-m-d', strtotime($peminjaman->tanggalPinjam));
                        } elseif ($peminjaman->hasAttribute('tglPinjam') && $peminjaman->tglPinjam) {
                            $tanggalPinjam = date('Y-m-d', strtotime($peminjaman->tglPinjam));
                        }
                        
                        // Ambil jatuh tempo
                        $jatuhTempo = 'N/A';
                        if ($peminjaman->tglJatuhTempo) {
                            $jatuhTempo = date('Y-m-d', strtotime($peminjaman->tglJatuhTempo));
                        }
                        
                        // Hitung sisa waktu
                        $sisaWaktu = '-';
                        $isTerlambat = false;
                        if ($peminjaman->tglJatuhTempo && $peminjaman->status !== 'dikembalikan') {
                            $tglJatuhTempo = strtotime($peminjaman->tglJatuhTempo);
                            $now = time();
                            $diff = $tglJatuhTempo - $now;
                            $days = floor($diff / (60 * 60 * 24));
                            
                            if ($days < 0) {
                                $sisaWaktu = abs($days) . ' hari terlambat';
                                $isTerlambat = true;
                            } elseif ($days == 0) {
                                $sisaWaktu = 'Hari ini';
                            } else {
                                $sisaWaktu = $days . ' hari lagi';
                            }
                        }
                        
                        // Status
                        $statusText = $peminjaman->status ?? 'N/A';
                        $statusClass = 'bg-gray-100 text-gray-800';
                        if ($statusText === 'dipinjam') {
                            $statusClass = 'bg-blue-100 text-blue-800';
                            $statusText = 'Dipinjam';
                        } elseif ($statusText === 'menunggu_verifikasi_admin') {
                            $statusClass = 'bg-yellow-100 text-yellow-800';
                            $statusText = 'Menunggu Verifikasi';
                        } elseif ($statusText === 'dikembalikan') {
                            $statusClass = 'bg-green-100 text-green-800';
                            $statusText = 'Dikembalikan';
                        } elseif ($isTerlambat) {
                            $statusClass = 'bg-red-100 text-red-800';
                            $statusText = 'Terlambat';
                        }
                        
                        // Ambil ID peminjaman untuk URL
                        $peminjamanId = $peminjaman->getId();
                        
                        // Denda
                        $dendaText = '-';
                        if ($peminjaman->status === 'dikembalikan' && $peminjamanId) {
                            // Cek apakah ada denda - validasi kolom terlebih dahulu
                            try {
                                $dendaTableSchema = \Yii::$app->db->getTableSchema('denda');
                                if ($dendaTableSchema) {
                                    $dendaColumns = array_keys($dendaTableSchema->columns);
                                    $dendaKey = null;
                                    
                                    // Cari kolom yang cocok untuk foreign key peminjaman
                                    if (in_array('peminjamanID', $dendaColumns)) {
                                        $dendaKey = 'peminjamanID';
                                    } elseif (in_array('peminjaman_id', $dendaColumns)) {
                                        $dendaKey = 'peminjaman_id';
                                    } elseif (in_array('peminjamanID', $dendaColumns)) {
                                        $dendaKey = 'peminjamanID';
                                    }
                                    
                                    if ($dendaKey) {
                                        $denda = \app\models\Denda::find()
                                            ->where([$dendaKey => $peminjamanId])
                                            ->one();
                                        if ($denda) {
                                            // Cek kolom jumlah yang ada
                                            $jumlahValue = 0;
                                            if (isset($denda->jumlah)) {
                                                $jumlahValue = $denda->jumlah;
                                            } elseif (isset($denda->total)) {
                                                $jumlahValue = $denda->total;
                                            } elseif (isset($denda->nominal)) {
                                                $jumlahValue = $denda->nominal;
                                            }
                                            $dendaText = 'Rp ' . number_format($jumlahValue, 0, ',', '.');
                                        }
                                    }
                                }
                            } catch (\Exception $e) {
                                // Jika error, skip pencarian denda
                                $dendaText = '-';
                            }
                        }
                        ?>
                        <tr class="group hover:bg-gray-50 transition-colors">
                            <td class="py-4 pl-4">
                                <p class="font-medium text-gray-900"><?= Html::encode($anggotaNama) ?></p>
                                <p class="text-xs text-gray-500">ID: <?= $peminjaman->anggotaID ?></p>
                            </td>
                            <td class="py-4 max-w-xs truncate pr-4" title="<?= Html::encode($judulBukuText) ?>">
                                <span class="text-gray-800"><?= Html::encode($judulBukuText) ?></span>
                            </td>
                            <td class="py-4 text-gray-700"><?= $tanggalPinjam ?></td>
                            <td class="py-4 text-gray-700"><?= $jatuhTempo ?></td>
                            <td class="py-4 <?= $isTerlambat ? 'text-red-600 font-medium' : 'text-gray-700' ?>">
                                <?= $sisaWaktu ?>
                            </td>
                            <td class="py-4">
                                <span class="text-xs px-2.5 py-1 rounded-full font-medium <?= $statusClass ?>">
                                    <?= $statusText ?>
                                </span>
                            </td>
                            <td class="py-4 text-gray-700 font-medium"><?= $dendaText ?></td>
                            <td class="py-4 text-right pr-4">
                                <?php if ($peminjaman->status === 'menunggu_verifikasi_admin'): ?>
                                    <?= Html::a('Verifikasi', ['transaksi/verifikasi-pinjaman', 'id' => $peminjamanId], [
                                        'class' => 'bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium py-2 px-4 rounded-lg inline-flex items-center transition-colors',
                                        'data' => [
                                            'method' => 'post',
                                            'confirm' => 'Apakah Anda yakin ingin memverifikasi peminjaman ini?',
                                        ],
                                    ]) ?>
                                <?php elseif ($peminjaman->status === 'dipinjam' || $isTerlambat): ?>
                                    <span class="text-gray-500 text-xs italic">Anggota akan mengembalikan sendiri</span>
                                <?php elseif ($peminjaman->status === 'dikembalikan'): ?>
                                    <span class="text-green-600 text-xs font-medium">✓ Sudah Dikembalikan</span>
                                <?php else: ?>
                                    <span class="text-gray-400 text-xs">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="mt-6 border-t border-gray-200 pt-4 flex justify-end">
        </div>

    </main>

</div>
