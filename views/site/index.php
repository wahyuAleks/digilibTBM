<?php
/* @var $this yii\web\View */
/* @var $bukuList app\models\Buku[] */
/* @var $kategoriList app\models\Kategori[] */
/* @var $keyword string */
/* @var $kategoriId string */

use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'Katalog Buku - TBM Digital';
$this->params['breadcrumbs'] = [];
?>

<div class="pt-2 pb-12">

    <div class="mb-8">
        <?php $form = \yii\widgets\ActiveForm::begin([
            'action' => Url::to(['site/index']),
            'method' => 'get',
            'options' => ['class' => 'relative'],
        ]); ?>
        <span class="absolute left-0 pl-3 top-3 flex items-start pointer-events-none">
            <svg class="h-5 w-5 text-gray-400 relative top-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
        </span>
        <input type="text" id="searchKatalog" name="keyword" value="<?= Html::encode($keyword) ?>" class="w-full bg-white border border-gray-300 text-gray-800 rounded-lg pl-10 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition shadow-sm" placeholder="Cari buku berdasarkan judul, penulis, atau ISBN...">
        
        <?php if (!empty($kategoriList)): ?>
        <div class="mt-4">
            <select name="kategori" onchange="this.form.submit()" class="w-full md:w-auto px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                <option value="">Semua Kategori</option>
                <?php foreach ($kategoriList as $kategori): ?>
                    <?php $kategoriIdValue = $kategori->kategoriID; ?>
                    <option value="<?= $kategoriIdValue ?>" <?= $kategoriId == $kategoriIdValue ? 'selected' : '' ?>>
                        <?= Html::encode($kategori->nama) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php endif; ?>
        
        <?php \yii\widgets\ActiveForm::end(); ?>
    </div>

    <div class="mb-4">
        <h3 class="text-lg font-bold text-gray-800">Koleksi Buku Terbaru</h3>
    </div>

    <?php if (empty($bukuList)): ?>
        <!-- Empty State -->
        <div class="bg-white border border-gray-200 rounded-xl p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Buku Tidak Ditemukan</h3>
            <p class="text-gray-500 mb-4">Maaf, tidak ada buku yang sesuai dengan kriteria pencarian Anda.</p>
            <a href="<?= Url::to(['site/index']) ?>" class="inline-block bg-black text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-800 transition-colors">
                Lihat Semua Buku
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            
            <?php foreach ($bukuList as $buku): ?>
            <div class="group bg-white rounded-xl border border-gray-200 overflow-hidden flex flex-col hover:shadow-lg transition-all duration-300">
                
                <?php 
                    $colors = ['bg-gray-800', 'bg-orange-400', 'bg-teal-600', 'bg-blue-800', 'bg-green-600', 'bg-purple-600', 'bg-rose-500'];
                    // Pilih warna berdasarkan ID buku biar konsisten
                    $randomColor = $colors[$buku->bukuID % count($colors)];
                ?>

<?php
                    // Sampul: prioritaskan thumbnail dari database, jika tidak ada cari gambar kategori lokal
                    $defaultCover = 'https://source.unsplash.com/featured/400x600/?book,' . urlencode($buku->kategori->nama ?? 'book');
                    $coverUrl = $defaultCover;
                    
                    // 1. CEK THUMBNAIL DI DATABASE TERLEBIH DAHULU
                    if (!empty($buku->thumbnail)) {
                        $thumbnailPath = Yii::getAlias('@webroot') . '/images/' . $buku->thumbnail;
                        if (file_exists($thumbnailPath)) {
                            $coverUrl = Url::to('@web/images/' . $buku->thumbnail);
                        }
                    } 
                    // 2. FALLBACK: CARI BERDASARKAN KATEGORI
                    else if (isset($buku->kategori) && !empty($buku->kategori->nama)) {
                        $catName = $buku->kategori->nama;
                        // Buat slug yang hanya berisi huruf dan angka dipisah oleh '-'
                        $slug = strtolower($catName);
                        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
                        $slug = trim($slug, '-');
                        $altSlug = str_replace('-', '', $slug); // fallback tanpa tanda hubung (nonfiksi)

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
                            // coba fallback ke SVG kategori khusus jika ada, jika tidak gunakan generic teknologi SVG yang sudah ada
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

                    <img src="<?= Html::encode($coverUrl) ?>" alt="Sampul buku <?= Html::encode($buku->judul) ?>" class="w-full h-full object-cover opacity-90 group-hover:scale-110 transition-transform duration-300" loading="lazy">
                    <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent p-3">
                        <span class="text-white text-xs font-semibold tracking-wide block line-clamp-2">
                            <?= Html::encode($buku->judul) ?>
                        </span>
                    </div>
                </div>

                <div class="p-4 flex flex-col flex-grow">
                    <div class="mb-2">
                        <?php if ($buku->kategori): ?>
                            <span class="inline-block bg-gray-100 text-gray-600 text-[10px] font-semibold px-2 py-1 rounded uppercase tracking-wide">
                                <?= Html::encode($buku->kategori->nama) ?>
                            </span>
                        <?php else: ?>
                            <span class="inline-block bg-gray-100 text-gray-600 text-[10px] font-semibold px-2 py-1 rounded uppercase tracking-wide">
                                Kategori #<?= $buku->kategoriID ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <h3 class="font-bold text-gray-900 text-sm mb-1 line-clamp-2 leading-snug">
                        <?= Html::encode($buku->judul) ?>
                    </h3>
                    <p class="text-xs text-gray-500 mb-4">
                        <?= Html::encode($buku->pengarang ?? '-') ?>
                    </p>

                    <div class="mt-auto">
                        <p class="text-[10px] text-gray-400 mb-2">
                            <?= Html::encode($buku->penerbit ?? '-') ?> • <?= Html::encode($buku->tahunTerbit ?? '-') ?>
                        </p>

                        <div class="space-y-2">
                            <a href="<?= Url::to(['/site/login']) ?>" class="block w-full text-center bg-blue-600 text-white text-xs font-semibold py-2.5 rounded-lg hover:bg-blue-700 transition-colors">
                                Login untuk Pinjam
                            </a>
                            
                            <a href="<?= Url::to(['/buku/view', 'id' => $buku->bukuID]) ?>" class="block w-full text-center border border-gray-300 text-gray-700 text-xs font-semibold py-2.5 rounded-lg hover:bg-black hover:text-white hover:border-transparent transition-colors">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

        </div>
    <?php endif; ?>

</div>

<script>
// Live search functionality untuk katalog publik
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchKatalog');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            const bookCards = document.querySelectorAll('.grid > div.group');
            const emptyState = document.querySelector('.bg-white.border.border-gray-200.rounded-xl.p-12');
            
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
            
            // Show/hide empty state based on results
            if (emptyState) {
                if (visibleCount === 0 && searchTerm.length > 0) {
                    emptyState.style.display = 'block';
                } else {
                    emptyState.style.display = 'none';
                }
            }
        });
    }
});
</script>
