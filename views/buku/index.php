<?php
use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BukuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

// Cek apakah user adalah admin
$isAdmin = !Yii::$app->user->isGuest && Yii::$app->user->identity->tipe_user === 'admin';

// Ambil data dari dataProvider
$models = $dataProvider->getModels();
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
                <h2 class="text-xl font-semibold text-gray-800"><?= $isAdmin ? 'Kelola Data Buku' : 'Katalog Buku' ?></h2>
                <p class="text-gray-500 text-sm"><?= $isAdmin ? 'Tambah, edit, atau hapus data buku perpustakaan' : 'Jelajahi koleksi buku perpustakaan' ?></p>
            </div>
            <?php if ($isAdmin): ?>
            <button onclick="openModal()" class="flex items-center bg-black text-white px-4 py-2 rounded-lg font-medium transition duration-150 hover:bg-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Buku
            </button>
            <?php endif; ?>
        </div>

        <div class="mb-6 flex items-center space-x-4">
            <?php
            // Kategori dropdown untuk filter (agar sama seperti katalog publik)
            $kategoriList = \yii\helpers\ArrayHelper::map(\app\models\Kategori::find()->all(), 'kategoriID', 'nama');
            $searchForm = \yii\widgets\ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
                'options' => ['class' => 'relative w-full md:w-1/2 flex items-center gap-3'],
            ]); ?>
            <div class="relative flex-1">
                <input type="text" name="BukuSearch[judul]" value="<?= Html::encode($searchModel->judul ?? '') ?>" placeholder="Cari berdasarkan judul..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <div class="w-48">
                <select name="BukuSearch[kategoriID]" onchange="this.form.submit()" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    <option value="">Semua Kategori</option>
                    <?php foreach ($kategoriList as $id => $nama): ?>
                        <option value="<?= $id ?>" <?= (isset($searchModel->kategoriID) && $searchModel->kategoriID == $id) ? 'selected' : '' ?>><?= Html::encode($nama) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <?php \yii\widgets\ActiveForm::end(); ?>
        </div>

        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="admin-table text-sm text-gray-600">
                <thead class="bg-gray-50 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-600">
                    <tr>
                        <th class="p-3 text-left w-[120px]">ISBN</th>
                        <th class="p-3 text-left w-[280px]">Judul</th>
                        <th class="p-3 text-left w-[160px]">Penulis</th>
                        <th class="p-3 text-left w-[120px]">Kategori</th>
                        <th class="p-3 text-left w-[50px]">Stok</th>
                        <th class="p-3 text-left w-[50px]">Tersedia</th>
                        <?php if ($isAdmin): ?>
                        <th class="p-3 text-center w-[120px]">Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($models)): ?>
                    <tr>
                        <td colspan="<?= $isAdmin ? '7' : '6' ?>" class="p-8 text-center text-gray-500">
                            Tidak ada data buku
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($models as $buku): ?>
                        <tr class="bg-white hover:bg-gray-50">
                            <td class="p-3 font-mono">-</td>
                            <td class="p-3">
                                <p class="font-medium text-gray-800"><?= Html::encode($buku->judul ?? 'N/A') ?></p>
                            </td>
                            <td class="p-3">-</td>
                            <td class="p-3">
                                <?php
                                $kategoriNama = '-';
                                if ($buku->kategori) {
                                    $kategoriNama = $buku->kategori->nama;
                                }
                                ?>
                                <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                    <?= Html::encode($kategoriNama) ?>
                                </span>
                            </td>
                            <td class="p-3"><?= Html::encode($buku->stok ?? 0) ?></td>
                            <td class="p-3 <?= ($buku->stok ?? 0) > 0 ? 'text-green-600' : 'text-red-500' ?> font-semibold">
                                <?= Html::encode($buku->stok ?? 0) ?>
                            </td>
                            <?php if ($isAdmin): ?>
                            <td class="p-3 text-center space-x-2">
                                <a href="<?= Url::to(['/buku/update', 'id' => $buku->bukuID]) ?>" class="text-blue-600 hover:text-blue-800 inline-block" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.586a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <?= Html::a(
                                    '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.86 10.933a2 2 0 01-1.993 1.776H7.853a2 2 0 01-1.993-1.776L5 7m14 0H5m11 0V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
                                    </svg>',
                                    ['/buku/delete', 'id' => $buku->bukuID],
                                    [
                                        'class' => 'text-red-600 hover:text-red-800 inline-block',
                                        'data' => [
                                            'method' => 'post',
                                            'confirm' => 'Apakah Anda yakin ingin menghapus buku "' . Html::encode($buku->judul) . '"? Tindakan ini tidak dapat dibatalkan.',
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
<!-- Modal Tambah Buku -->
<div id="modalTambahBuku" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <!-- Background overlay -->
    <div class="fixed inset-0 bg-gray-500 bg-opacity-50 transition-opacity" onclick="closeModal()"></div>

    <!-- Modal panel - Centered box -->
    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto transform transition-all duration-300 ease-in-out">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Tambah Buku Baru</h3>
                    <p class="text-sm text-gray-500 mt-1">Masukkan informasi buku yang akan ditambahkan</p>
                </div>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <?php
            $model = new \app\models\Buku();
            $kategoriList = \yii\helpers\ArrayHelper::map(\app\models\Kategori::find()->all(), 'kategoriID', 'nama');
            $rakList = \yii\helpers\ArrayHelper::map(\app\models\Rak::find()->all(), 'id', 'nama');
            $form = \yii\widgets\ActiveForm::begin([
                'action' => Url::to(['buku/create']),
                'method' => 'post',
                'options' => ['class' => 'space-y-4'],
            ]);
            ?>

            <div class="space-y-4 max-h-[60vh] overflow-y-auto pr-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ISBN</label>
                    <input type="text" name="isbn" placeholder="978-0-13-468599-1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                <?= $form->field($model, 'judul', [
                    'template' => '<div><label class="block text-sm font-medium text-gray-700 mb-1">{label}</label>{input}{error}</div>',
                    'labelOptions' => ['label' => 'Judul Buku'],
                    'inputOptions' => [
                        'class' => 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm',
                        'placeholder' => 'Masukkan judul buku'
                    ],
                ])->textInput() ?>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Penulis</label>
                    <input type="text" name="pengarang" placeholder="Nama penulis" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Penerbit</label>
                    <input type="text" name="penerbit" placeholder="Nama penerbit" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Terbit</label>
                    <input type="text" name="tahunTerbit" placeholder="2025" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                <?= $form->field($model, 'kategoriID', [
                    'template' => '<div><label class="block text-sm font-medium text-gray-700 mb-1">{label}</label>{input}{error}</div>',
                    'labelOptions' => ['label' => 'Kategori'],
                    'inputOptions' => [
                        'class' => 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm',
                    ],
                ])->dropDownList($kategoriList, ['prompt' => 'Pilih Kategori']) ?>

                <?php
                // Hanya tampilkan field rakID jika kolomnya ada di database
                try {
                    if ($model->hasAttribute('rakID')) {
                        echo $form->field($model, 'rakID', [
                            'template' => '<div><label class="block text-sm font-medium text-gray-700 mb-1">{label}</label>{input}{error}</div>',
                            'labelOptions' => ['label' => 'Rak'],
                            'inputOptions' => [
                                'class' => 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm',
                            ],
                        ])->dropDownList($rakList, ['prompt' => 'Pilih Rak']);
                    }
                } catch (\Exception $e) {
                    // Skip jika kolom tidak ada
                }
                ?>

                <?= $form->field($model, 'stok', [
                    'template' => '<div><label class="block text-sm font-medium text-gray-700 mb-1">{label}</label>{input}{error}</div>',
                    'labelOptions' => ['label' => 'Jumlah Stok'],
                    'inputOptions' => [
                        'class' => 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm',
                        'type' => 'number',
                        'value' => '0',
                        'min' => '0'
                    ],
                ])->textInput() ?>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tersedia</label>
                    <input type="number" name="tersedia" id="tersedia" value="0" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" readonly>
                    <p class="text-xs text-gray-500 mt-1">Akan sama dengan jumlah stok saat pertama kali ditambahkan</p>
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

            <?php \yii\widgets\ActiveForm::end(); ?>
        </div>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('modalTambahBuku').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('modalTambahBuku').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal on Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeModal();
    }
});

// Sync tersedia dengan stok
document.addEventListener('DOMContentLoaded', function() {
    const stokInput = document.querySelector('input[name="Buku[stok]"]');
    const tersediaInput = document.getElementById('tersedia');
    
    if (stokInput && tersediaInput) {
        stokInput.addEventListener('input', function() {
            tersediaInput.value = this.value || 0;
        });
    }
});
</script>
<?php endif; ?>