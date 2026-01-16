<?php
/* @var $this yii\web\View */
/* @var $bukuList app\models\Buku[] */ // Kita kasih tahu view kalau ini isinya data Buku

use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'Portal Anggota';
?>

<div class="pt-2 pb-12">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <a href="<?= Url::to(['/member/peminjaman', 'tab' => 'aktif']) ?>" class="bg-white border border-gray-200 rounded-xl p-5 flex justify-between items-center shadow-sm hover:shadow-md transition-shadow">
            <div>
                <p class="text-sm font-medium text-gray-500">Sedang Dipinjam</p>
                <p class="text-2xl font-bold text-gray-900 mt-1"><?= $peminjamanAktif ?? 0 ?></p>
                <p class="text-xs text-gray-400 mt-0.5">buku aktif</p>
            </div>
            <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
            </div>
        </a>

        <a href="<?= Url::to(['/member/peminjaman', 'tab' => 'aktif']) ?>" class="bg-white border border-gray-200 rounded-xl p-5 flex justify-between items-center shadow-sm hover:shadow-md transition-shadow">
            <div>
                <p class="text-sm font-medium text-gray-500">Terlambat</p>
                <p class="text-2xl font-bold text-gray-900 mt-1"><?= $peminjamanTerlambat ?? 0 ?></p>
                <p class="text-xs text-gray-400 mt-0.5">perlu dikembalikan</p>
            </div>
            <div class="p-2 bg-red-50 text-red-600 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
        </a>

        <div class="bg-white border border-gray-200 rounded-xl p-5 flex justify-between items-center shadow-sm">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Denda</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">Rp <?= number_format($totalDenda ?? 0, 0, ',', '.') ?></p>
                <p class="text-xs text-gray-400 mt-0.5">yang harus dibayar</p>
            </div>
            <div class="p-2 bg-yellow-50 text-yellow-600 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
            </div>
        </div>
    </div>

    <div class="mb-8">
        <?php $form = \yii\widgets\ActiveForm::begin([
            'action' => Url::to(['member/index']),
            'method' => 'get',
            'options' => ['class' => 'relative'],
        ]); ?>
        <span class="absolute left-0 pl-3 top-3 flex items-start pointer-events-none">
            <svg class="h-5 w-5 text-gray-400 relative top-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
        </span>
        <input type="text" id="searchBuku" name="search" value="<?= Html::encode(Yii::$app->request->get('search', '')) ?>" class="w-full bg-white border border-gray-300 text-gray-800 rounded-lg pl-10 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition shadow-sm" placeholder="Cari berdasarkan judul, penulis, atau ISBN...">
        <?php \yii\widgets\ActiveForm::end(); ?>
    </div>

    <div class="mb-4 flex items-center justify-between">
        <div>
            <h3 class="text-lg font-bold text-gray-800">Koleksi Buku Terbaru</h3>
            <p class="text-xs text-gray-500 mt-1">
                Temukan dan pinjam buku yang tersedia di perpustakaan digital TBM Anda.
            </p>
        </div>
        <?php if (!empty($bukuList)): ?>
            <span class="text-[11px] text-gray-400">
                Menampilkan <?= count($bukuList) ?> buku
            </span>
        <?php endif; ?>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        
        <?php if (empty($bukuList)): ?>
            <div class="col-span-full">
                <div class="bg-white border border-dashed border-gray-300 rounded-xl p-8 text-center text-gray-500 text-sm">
                    Belum ada buku yang terdaftar. Silakan hubungi admin untuk menambahkan koleksi buku.
                </div>
            </div>
        <?php else: ?>
        <?php foreach ($bukuList as $buku): ?>
        <div class="group bg-white rounded-xl border border-gray-200 overflow-hidden flex flex-col hover:shadow-lg transition-all duration-300">
            
            <?php 
                $colors = ['bg-gray-800', 'bg-orange-400', 'bg-teal-600', 'bg-blue-800', 'bg-green-600', 'bg-purple-600', 'bg-rose-500'];
                // Pilih warna berdasarkan ID buku biar konsisten
                $randomColor = $colors[$buku->bukuID % count($colors)];

                // Gambar sampul dinamis (gunakan koleksi gambar lokal untuk kategori "Teknologi" jika tersedia)
                $kategoriLabel = 'book';
                if (isset($buku->kategori) && !empty($buku->kategori->nama)) {
                    $kategoriLabel = strtolower($buku->kategori->nama);
                }
                $coverUrl = 'https://source.unsplash.com/featured/400x600/?book,' . urlencode($kategoriLabel);
                if (isset($buku->kategori) && !empty($buku->kategori->nama)) {
                    $catName = $buku->kategori->nama;
                    $slug = strtolower($catName);
                    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
                    $slug = trim($slug, '-');
                    $altSlug = str_replace('-', '', $slug);

                    $imageDir = Yii::getAlias('@webroot') . '/images';
                    $patterns = [
                        $slug . '-cover*.jpg', $slug . '-cover*.jpeg', $slug . '-cover*.png', $slug . '-cover*.webp',
                        $altSlug . '-cover*.jpg', $altSlug . '-cover*.png', $altSlug . '-cover*.webp'
                    ];
                    $found = [];
                    foreach ($patterns as $p) {
                        $matches = glob($imageDir . '/' . $p);
                        if (!empty($matches)) {
                            $found = array_merge($found, $matches);
                        }
                    }
                    if (!empty($found)) {
                        $urls = array_map(function($path) { return str_replace(Yii::getAlias('@webroot'), Yii::getAlias('@web'), $path); }, $found);
                        $index = $buku->bukuID % count($urls);
                        $coverUrl = Url::to($urls[$index]);
                    } else {
                        $svgPath = Yii::getAlias('@webroot') . '/images/' . $slug . '-cover.svg';
                        if (file_exists($svgPath)) {
                            $coverUrl = Url::to('@web/images/' . $slug . '-cover.svg');
                        } else {
                            $coverUrl = Url::to('@web/images/teknologi-cover.svg');
                        }
                    }
                }
            ?>

            <div class="relative w-full aspect-[3/4] <?= $randomColor ?> flex items-center justify-center overflow-hidden">
                <?php if ($buku->stok > 0): ?>
                    <div class="absolute top-3 right-3 bg-black/60 backdrop-blur-sm text-white text-[10px] font-bold px-2 py-1 rounded-md">
                        <?= $buku->stok ?> tersedia
                    </div>
                <?php else: ?>
                    <div class="absolute top-3 right-3 bg-red-600 text-white text-[10px] font-bold px-2 py-1 rounded-md">
                        Habis
                    </div>
                <?php endif; ?>

                <img
                    src="<?= Html::encode($coverUrl) ?>"
                    alt="Sampul buku <?= Html::encode($buku->judul) ?>"
                    class="w-full h-full object-cover opacity-90 group-hover:scale-110 transition-transform duration-300"
                    loading="lazy"
                >

                <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent p-3">
                    <span class="text-white text-xs font-semibold tracking-wide block line-clamp-2">
                        <?= $buku->judul ?>
                    </span>
                    <span class="text-[10px] text-gray-200 block mt-1">
                        <?= isset($buku->kategori) && !empty($buku->kategori->nama)
                            ? Html::encode($buku->kategori->nama)
                            : 'Kategori tidak diketahui' ?>
                    </span>
                </div>
            </div>

            <div class="p-4 flex flex-col flex-grow">
                <div class="mb-2">
                    <span class="inline-block bg-gray-100 text-gray-600 text-[10px] font-semibold px-2 py-1 rounded uppercase tracking-wide">
                        Kategori #<?= $buku->kategoriID ?>
                    </span>
                </div>

                <h3 class="font-bold text-gray-900 text-sm mb-1 line-clamp-2 leading-snug">
                    <?= $buku->judul ?>
                </h3>
                <p class="text-xs text-gray-500 mb-4 line-clamp-2">
                    Kategori:
                    <span class="font-semibold">
                        <?= isset($buku->kategori) && !empty($buku->kategori->nama)
                            ? Html::encode($buku->kategori->nama)
                            : 'Belum dikategorikan' ?>
                    </span>
                </p>

                <div class="mt-auto">
                    <p class="text-[10px] text-gray-400 mb-2">
                        ID Buku: <?= $buku->bukuID ?> • Stok: <?= (int)($buku->stok ?? 0) ?>
                    </p>

                    <div class="space-y-2">
                        <?php if ($buku->stok > 0): ?>
                            <?= Html::a('Pinjam Buku', ['anggota/pinjam-online', 'bukuId' => $buku->bukuID], [
                                'class' => 'block w-full text-center bg-blue-600 text-white text-xs font-semibold py-2.5 rounded-lg hover:bg-blue-700 transition-colors',
                                'data' => [
                                    'confirm' => 'Apakah Anda yakin ingin meminjam buku "' . Html::encode($buku->judul) . '"?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        <?php else: ?>
                            <button disabled class="block w-full text-center bg-gray-300 text-gray-500 text-xs font-semibold py-2.5 rounded-lg cursor-not-allowed">
                                Stok Habis
                            </button>
                        <?php endif; ?>
                        
                        <a href="<?= Url::to(['/buku/view', 'id' => $buku->bukuID]) ?>" class="block w-full text-center border border-gray-300 text-gray-700 text-xs font-semibold py-2.5 rounded-lg hover:bg-black hover:text-white hover:border-transparent transition-colors">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>

    </div>

</div>

<script>
// Live search functionality untuk buku
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchBuku');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            const bookCards = document.querySelectorAll('.grid > div[class*="group"]');
            const emptyMessage = document.querySelector('.col-span-full');
            
            let visibleCount = 0;
            
            bookCards.forEach(function(card) {
                // Cari judul buku dalam card (ada di tag h3)
                const titleElement = card.querySelector('h3');
                if (titleElement) {
                    const title = titleElement.textContent.toLowerCase();
                    
                    // Show card jika judul cocok dengan search term
                    if (title.includes(searchTerm)) {
                        card.style.display = '';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                }
            });
            
            // Update counter jumlah buku yang ditampilkan
            const counterElement = document.querySelector('.text-\\[11px\\].text-gray-400');
            if (counterElement) {
                counterElement.textContent = 'Menampilkan ' + visibleCount + ' buku';
            }
        });
    }
});
</script>
