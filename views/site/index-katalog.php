<?php
/* @var $this yii\web\View */
/* @var $bukuList app\models\Buku[] */
/* @var $kategoriList app\models\Kategori[] */
/* @var $kategoriId string */

use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'Katalog Buku - TBM Digital';
?>

<div class="pt-6">
    <div class="mb-8">
        <h2 class="text-2xl font-semibold text-gray-800">Katalog Buku</h2>
        <p class="text-gray-500 mt-1 text-sm">Jelajahi koleksi buku perpustakaan kami</p>
    </div>

    <div class="mb-8">
        <?php $form = \yii\widgets\ActiveForm::begin([
            'action' => Url::to(['site/index']),
            'method' => 'get',
            'options' => ['class' => 'relative'],
        ]); ?>
        <span class="absolute left-0 pl-3 top-3 flex items-start pointer-events-none">
            <svg class="h-5 w-5 text-gray-400 relative top-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </span>
        <input type="text" name="search" value="<?= Html::encode(Yii::$app->request->get('search', '')) ?>" class="w-full bg-white border border-gray-300 text-gray-800 rounded-lg pl-10 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition shadow-sm" placeholder="Cari buku berdasarkan judul, penulis, atau ISBN...">        <?php if (!empty($kategoriList)): ?>
        <div class="mt-4">
            <select name="kategori" onchange="this.form.submit()" class="w-full md:w-auto px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                <option value="">Semua Kategori</option>
                <?php foreach ($kategoriList as $kategori): ?>
                    <?php $kategoriIdValue = $kategori->kategoriID; ?>
                    <option value="<?= $kategoriIdValue ?>" <?= (string)$kategoriId == (string)$kategoriIdValue ? 'selected' : '' ?>>
                        <?= Html::encode($kategori->nama) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php endif; ?>        <?php \yii\widgets\ActiveForm::end(); ?>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php if (isset($bukuList) && !empty($bukuList)): ?>
            <?php foreach ($bukuList as $buku): ?>
            <div class="group bg-white rounded-xl border border-gray-200 overflow-hidden flex flex-col hover:shadow-lg transition-all duration-300">
<?php
                    // Tentukan sampul buku. Untuk kategori Teknologi, cari semua gambar di web/images yang memiliki nama 'teknologi-cover*'.
                    $defaultCover = 'https://source.unsplash.com/featured/400x600/?book,' . urlencode($buku->kategori->nama ?? 'book');
                    $coverUrl = $defaultCover;
                    if (isset($buku->kategori) && !empty($buku->kategori->nama)) {
                        $catName = $buku->kategori->nama;
                        $slug = strtolower($catName);
                        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
                        $slug = trim($slug, '-');
                        $altSlug = str_replace('-', '', $slug);

n                        $imageDir = Yii::getAlias('@webroot') . '/images';
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
                <div class="relative w-full aspect-[3/4] bg-gray-800 flex items-center justify-center overflow-hidden">
                    <img src="<?= Html::encode($coverUrl) ?>" alt="Sampul buku <?= Html::encode($buku->judul) ?>" class="w-full h-full object-cover opacity-90 group-hover:scale-110 transition-transform duration-300" loading="lazy">
                    <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent p-3">
                        <span class="text-white text-xs font-semibold tracking-wider block line-clamp-2">
                            <?= Html::encode($buku->judul) ?>
                        </span>
                    </div>
                </div>

                <div class="p-4 flex flex-col flex-grow">
                    <h3 class="font-bold text-gray-900 text-sm mb-1 line-clamp-2 leading-snug">
                        <?= Html::encode($buku->judul) ?>
                    </h3>
                    <p class="text-xs text-gray-500 mb-4">
                        <?= Html::encode($buku->pengarang ?? '-') ?>
                    </p>

                    <div class="mt-auto">
                        <?php if (Yii::$app->user->isGuest): ?>
                            <a href="<?= Url::to(['/site/login']) ?>" class="block w-full text-center bg-black text-white text-xs font-semibold py-2.5 rounded-lg hover:bg-gray-800 transition-colors">
                                Login untuk Pinjam
                            </a>
                        <?php else: ?>
                            <a href="<?= Url::to(['/buku/view', 'id' => $buku->bukuID]) ?>" class="block w-full text-center border border-gray-300 text-gray-700 text-xs font-semibold py-2.5 rounded-lg hover:bg-black hover:text-white hover:border-transparent transition-colors">
                                Lihat Detail
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-span-full text-center py-12">
                <p class="text-gray-500">Tidak ada buku ditemukan.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

