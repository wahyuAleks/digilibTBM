<?php
/* @var $this yii\web\View */
/* @var $peminjamans app\models\Peminjaman[] */
/* @var $totalPeminjaman int */
/* @var $sedangDipinjam int */
/* @var $menungguVerifikasi int */
/* @var $sudahDikembalikan int */
/* @var $activeTab string */

use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'Riwayat Peminjaman';

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

<div class="pt-2 pb-12">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <div class="bg-white border border-gray-200 rounded-2xl p-6 flex flex-col justify-between h-32 shadow-sm">
            <div class="flex justify-between items-start">
                <span class="text-sm font-medium text-gray-700">Total Peminjaman</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <span class="text-3xl font-medium text-gray-900"><?= $totalPeminjaman ?></span>
                <p class="text-xs text-gray-400 mt-1">semua waktu</p>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-6 flex flex-col justify-between h-32 shadow-sm">
            <div class="flex justify-between items-start">
                <span class="text-sm font-medium text-gray-700">Sedang Dipinjam</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <span class="text-3xl font-medium text-gray-900"><?= $sedangDipinjam ?></span>
                <p class="text-xs text-gray-400 mt-1">buku aktif</p>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-6 flex flex-col justify-between h-32 shadow-sm">
            <div class="flex justify-between items-start">
                <span class="text-sm font-medium text-gray-700">Sudah Dikembalikan</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <span class="text-3xl font-medium text-gray-900"><?= $sudahDikembalikan ?></span>
                <p class="text-xs text-gray-400 mt-1">selesai</p>
            </div>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl p-6 min-h-[500px] shadow-sm">
        
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-900">Riwayat Peminjaman</h2>
            <p class="text-sm text-gray-500">Lihat semua riwayat peminjaman buku Anda</p>
        </div>

        <div class="mb-8">
            <div class="bg-gray-100 p-1 rounded-xl flex font-medium text-sm w-full">
                <a href="<?= Url::to(['peminjaman', 'tab' => 'aktif']) ?>" class="flex-1 py-2.5 px-4 text-center rounded-lg transition-all <?= $activeTab === 'aktif' ? 'bg-white text-gray-900 shadow-sm font-semibold' : 'text-gray-500 hover:text-gray-700' ?>">
                    Sedang Dipinjam (<?= $sedangDipinjam ?>)
                </a>
                <a href="<?= Url::to(['peminjaman', 'tab' => 'menunggu']) ?>" class="flex-1 py-2.5 px-4 text-center rounded-lg transition-all <?= $activeTab === 'menunggu' ? 'bg-white text-gray-900 shadow-sm font-semibold' : 'text-gray-500 hover:text-gray-700' ?>">
                    Menunggu Verifikasi (<?= $menungguVerifikasi ?? 0 ?>)
                </a>
                <a href="<?= Url::to(['peminjaman', 'tab' => 'riwayat']) ?>" class="flex-1 py-2.5 px-4 text-center rounded-lg transition-all <?= $activeTab === 'riwayat' ? 'bg-white text-gray-900 shadow-sm font-semibold' : 'text-gray-500 hover:text-gray-700' ?>">
                    Riwayat (<?= $sudahDikembalikan ?>)
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="text-gray-900 font-semibold border-b border-gray-100">
                    <tr>
                        <th class="pb-4 pl-2 font-medium text-gray-900">Judul Buku</th>
                        <th class="pb-4 font-medium text-gray-900">Tanggal Pinjam</th>
                        <th class="pb-4 font-medium text-gray-900">Jatuh Tempo</th>
                        <th class="pb-4 font-medium text-gray-900">Status</th>
                        <th class="pb-4 font-medium text-gray-900">Denda</th>
                        <th class="pb-4 font-medium text-gray-900 text-right pr-2">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 divide-y divide-gray-50">
                    <?php if (empty($peminjamans)): ?>
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-500">
                            Tidak ada data peminjaman
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($peminjamans as $peminjaman): ?>
                        <?php
                        // Ambil judul buku dari item peminjaman
                        $judulBuku = [];
                        $judulBukuText = 'N/A';
                        $peminjamanId = $peminjaman->getId();
                        
                        try {
                            // Cek apakah tabel item_peminjaman ada
                            $tableSchema = \Yii::$app->db->getTableSchema('item_peminjaman');
                            if ($tableSchema) {
                                // Cek apakah relasi itemPeminjamans sudah ter-load
                                if (isset($peminjaman->itemPeminjamans) && !empty($peminjaman->itemPeminjamans)) {
                                    foreach ($peminjaman->itemPeminjamans as $item) {
                                        if ($item && isset($item->buku) && $item->buku) {
                                            $judulBuku[] = $item->buku->judul ?? 'N/A';
                                        }
                                    }
                                    $judulBukuText = !empty($judulBuku) ? implode(', ', $judulBuku) : 'N/A';
                                } else {
                                    // Jika relasi belum ter-load, coba load manual
                                    try {
                                        // Cari kolom foreign key yang benar
                                        $columns = array_keys($tableSchema->columns);
                                        $peminjamanKey = 'peminjamanID';
                                        if (!in_array('peminjamanID', $columns)) {
                                            if (in_array('peminjaman_id', $columns)) {
                                                $peminjamanKey = 'peminjaman_id';
                                            } elseif (in_array('id', $columns)) {
                                                $peminjamanKey = 'id';
                                            }
                                        }
                                        
                                        $itemPeminjamans = \app\models\ItemPeminjaman::find()
                                            ->where([$peminjamanKey => $peminjamanId])
                                            ->with('buku')
                                            ->all();
                                        
                                        if (!empty($itemPeminjamans)) {
                                            foreach ($itemPeminjamans as $item) {
                                                if ($item && $item->buku) {
                                                    $judulBuku[] = $item->buku->judul ?? 'N/A';
                                                }
                                            }
                                            $judulBukuText = !empty($judulBuku) ? implode(', ', $judulBuku) : 'N/A';
                                        } else {
                                            // Jika tidak ada di item_peminjaman, coba query langsung
                                            $db = \Yii::$app->db;
                                            $itemPeminjamanData = $db->createCommand(
                                                "SELECT bukuID FROM item_peminjaman WHERE {$peminjamanKey} = :peminjamanID",
                                                [':peminjamanID' => $peminjamanId]
                                            )->queryAll();
                                            
                                            if (!empty($itemPeminjamanData)) {
                                                $bukuIds = array_column($itemPeminjamanData, 'bukuID');
                                                $bukuList = \app\models\Buku::find()
                                                    ->where(['bukuID' => $bukuIds])
                                                    ->all();
                                                
                                                foreach ($bukuList as $buku) {
                                                    $judulBuku[] = $buku->judul ?? 'N/A';
                                                }
                                                $judulBukuText = !empty($judulBuku) ? implode(', ', $judulBuku) : 'N/A';
                                            }
                                        }
                                    } catch (\Exception $e2) {
                                        // Jika masih error, coba query langsung ke database
                                        try {
                                            $db = \Yii::$app->db;
                                            $columns = array_keys($tableSchema->columns);
                                            $peminjamanKey = in_array('peminjamanID', $columns) ? 'peminjamanID' : 
                                                           (in_array('peminjaman_id', $columns) ? 'peminjaman_id' : 'id');
                                            
                                            $itemPeminjamanData = $db->createCommand(
                                                "SELECT bukuID FROM item_peminjaman WHERE {$peminjamanKey} = :peminjamanID",
                                                [':peminjamanID' => $peminjamanId]
                                            )->queryAll();
                                            
                                            if (!empty($itemPeminjamanData)) {
                                                $bukuIds = array_column($itemPeminjamanData, 'bukuID');
                                                $bukuList = \app\models\Buku::find()
                                                    ->where(['bukuID' => $bukuIds])
                                                    ->all();
                                                
                                                foreach ($bukuList as $buku) {
                                                    $judulBuku[] = $buku->judul ?? 'N/A';
                                                }
                                                $judulBukuText = !empty($judulBuku) ? implode(', ', $judulBuku) : 'N/A';
                                            }
                                        } catch (\Exception $e3) {
                                            // Jika semua gagal, set default
                                            $judulBukuText = 'N/A';
                                        }
                                    }
                                }
                            } else {
                                // Tabel tidak ada
                                $judulBukuText = 'N/A (Tabel item_peminjaman tidak ditemukan)';
                            }
                        } catch (\Exception $e) {
                            // Jika error, set default
                            $judulBukuText = 'N/A';
                        }
                        
                        // Ambil tanggal pinjam
                        $tanggalPinjam = 'N/A';
                        if ($peminjaman->hasAttribute('tanggalPinjam') && $peminjaman->tanggalPinjam) {
                            $tanggalPinjam = date('d-m-Y', strtotime($peminjaman->tanggalPinjam));
                        } elseif ($peminjaman->hasAttribute('tglPinjam') && $peminjaman->tglPinjam) {
                            $tanggalPinjam = date('d-m-Y', strtotime($peminjaman->tglPinjam));
                        }
                        
                        // Ambil jatuh tempo
                        $jatuhTempo = 'N/A';
                        $isTerlambat = false;
                        $hariTerlambat = 0;
                        if ($peminjaman->tglJatuhTempo) {
                            $jatuhTempo = date('d-m-Y', strtotime($peminjaman->tglJatuhTempo));
                            
                            // Cek apakah terlambat
                            if ($peminjaman->status !== 'dikembalikan') {
                                $tglJatuhTempo = strtotime($peminjaman->tglJatuhTempo);
                                $now = time();
                                if ($tglJatuhTempo < $now) {
                                    $isTerlambat = true;
                                    $hariTerlambat = floor(($now - $tglJatuhTempo) / (60 * 60 * 24));
                                }
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
                        }
                        
                        // Denda
                        $dendaText = '-';
                        $peminjamanId = $peminjaman->getId();
                        if ($peminjaman->status === 'dikembalikan') {
                            // Cek apakah ada denda
                            try {
                                $denda = \app\models\Denda::findOne(['peminjamanID' => $peminjamanId]);
                                if ($denda) {
                                    $dendaText = 'Rp ' . number_format($denda->jumlah ?? 0, 0, ',', '.');
                                }
                            } catch (\Exception $e) {
                                // Skip jika error
                            }
                        } elseif ($isTerlambat) {
                            // Hitung denda potensial
                            $dendaPotensial = $hariTerlambat * 500;
                            $dendaText = 'Rp ' . number_format($dendaPotensial, 0, ',', '.') . ' (potensial)';
                        }
                        ?>
                        <tr class="group hover:bg-gray-50 transition-colors">
                            <td class="py-5 pl-2 align-top">
                                <p class="text-gray-900 text-[15px]"><?= Html::encode($judulBukuText) ?></p>
                            </td>
                            <td class="py-5 align-top">
                                <?= $tanggalPinjam ?>
                            </td>
                            <td class="py-5 align-top">
                                <p><?= $jatuhTempo ?></p>
                                <?php if ($isTerlambat && $peminjaman->status !== 'dikembalikan'): ?>
                                    <p class="text-xs text-red-500 mt-1 font-medium"><?= $hariTerlambat ?> hari terlambat</p>
                                <?php endif; ?>
                            </td>
                            <td class="py-5 align-top">
                                <span class="text-xs px-3 py-1.5 rounded-full font-medium <?= $statusClass ?>">
                                    <?= $statusText ?>
                                </span>
                            </td>
                            <td class="py-5 align-top">
                                <?= $dendaText ?>
                            </td>
                            <td class="py-5 align-top text-right pr-2">
                                <?php if ($peminjaman->status === 'dipinjam'): ?>
                                    <?= Html::a('Kembalikan', ['anggota/pengembalian-mandiri', 'peminjamanId' => $peminjamanId], [
                                        'class' => 'bg-black hover:bg-gray-800 text-white text-xs font-medium py-2 px-4 rounded-lg inline-flex items-center transition-colors',
                                        'data' => [
                                            'method' => 'post',
                                            'confirm' => 'Apakah Anda yakin ingin mengembalikan buku ini? Denda (jika ada) akan dibayarkan secara tunai di tempat.',
                                        ],
                                    ]) ?>
                                <?php elseif ($peminjaman->status === 'menunggu_verifikasi_admin'): ?>
                                    <span class="text-gray-500 text-xs italic">Menunggu verifikasi admin</span>
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
        
        <div class="mt-8"></div>

    </div>

</div>
