<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;

AppAsset::register($this);
$route = Yii::$app->controller->route; 
$user = Yii::$app->user->isGuest ? null : Yii::$app->user->identity; // Ambil data user yang sedang login, null jika guest
$isGuest = Yii::$app->user->isGuest;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-full bg-gray-50">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
    <?php $this->head() ?>
</head>
<body class="h-full text-gray-800">
<?php $this->beginBody() ?>

<div class="min-h-full">
    <div class="bg-white border-b border-gray-200 mb-8 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 mb-2">
                
                <div class="flex items-center gap-3">
                    <div class="bg-blue-600 text-white p-1.5 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    </div>
                    <div>
                        <h1 class="font-bold text-lg leading-none text-gray-900">TBM Digital</h1>
                        <span class="text-xs text-gray-500 font-medium">
                            <?php if (!$isGuest): ?>
                                <?= $user->tipe_user === 'admin' ? 'Admin Dashboard' : 'Member Area' ?>
                            <?php else: ?>
                                Katalog Publik
                            <?php endif; ?>
                        </span>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <?php if (!$isGuest): ?>
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-medium text-gray-900"><?= Html::encode($user->nama ?? 'User') ?></p>
                            <p class="text-xs text-gray-500 capitalize"><?= Html::encode($user->tipe_user ?? '') ?></p>
                        </div>
                        <a href="<?= Url::to(['/site/logout']) ?>" data-method="post" class="flex items-center gap-2 px-3 py-2 rounded-md border border-gray-200 hover:bg-gray-50 hover:text-red-600 transition-colors text-sm font-medium text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                            Logout
                        </a>
                    <?php else: ?>
                        <a href="<?= Url::to(['/site/login']) ?>" class="flex items-center gap-2 px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 transition-colors text-sm font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                            Login
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!$isGuest): ?>
            <div class="pb-4">
                <nav class="bg-gray-100 p-1.5 rounded-full flex items-center justify-between overflow-x-auto">
                    
                    <?php if ($user && $user->tipe_user === 'admin'): ?>
                        <?php
                        // Hitung pinjaman yang menunggu verifikasi
                        $menungguVerifikasi = \app\models\Peminjaman::find()
                            ->where(['status' => 'menunggu_verifikasi_admin'])
                            ->count();
                        ?>
                        <a href="<?= Url::to(['/site/dashboard']) ?>" class="flex-1 text-center py-2 px-4 rounded-full font-medium text-sm transition-all <?= strpos($route, 'site/dashboard') !== false ? 'bg-blue-600 text-white shadow-sm' : 'bg-blue-50 text-blue-600 hover:bg-blue-100' ?>">Dashboard</a>
                        <a href="<?= Url::to(['/buku/index']) ?>" class="flex-1 text-center py-2 px-4 rounded-full font-medium text-sm transition-all <?= strpos($route, 'buku') !== false ? 'bg-blue-600 text-white shadow-sm' : 'bg-blue-50 text-blue-600 hover:bg-blue-100' ?>">Kelola Buku</a>
                        <a href="<?= Url::to(['/anggota/index']) ?>" class="flex-1 text-center py-2 px-4 rounded-full font-medium text-sm transition-all <?= strpos($route, 'anggota') !== false ? 'bg-blue-600 text-white shadow-sm' : 'bg-blue-50 text-blue-600 hover:bg-blue-100' ?>">Kelola Anggota</a>
                        <a href="<?= Url::to(['/peminjaman/index', 'tab' => 'aktif']) ?>" class="flex-1 text-center py-2 px-4 rounded-full font-medium text-sm transition-all relative <?= strpos($route, 'peminjaman') !== false ? 'bg-blue-600 text-white shadow-sm' : 'bg-blue-50 text-blue-600 hover:bg-blue-100' ?>">
                            Peminjaman
                            <?php if ($menungguVerifikasi > 0): ?>
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center"><?= $menungguVerifikasi ?></span>
                            <?php endif; ?>
                        </a>

                    <?php else: ?>
                        <a href="<?= Url::to(['/site/dashboard-anggota']) ?>" class="flex-1 text-center py-2 px-4 rounded-full font-medium text-sm transition-all <?= strpos($route, 'site/dashboard-anggota') !== false ? 'bg-blue-600 text-white shadow-sm' : 'bg-blue-50 text-blue-600 hover:bg-blue-100' ?>">Dashboard</a>
                        
                        <a href="<?= Url::to(['/member/index']) ?>" class="flex-1 text-center py-2 px-4 rounded-full font-medium text-sm transition-all <?= $route == 'member/index' ? 'bg-blue-600 text-white shadow-sm' : 'bg-blue-50 text-blue-600 hover:bg-blue-100' ?>">Jelajah Buku</a>
                        
                        <a href="<?= Url::to(['/member/peminjaman']) ?>" class="flex-1 text-center py-2 px-4 rounded-full font-medium text-sm transition-all <?= $route == 'member/peminjaman' ? 'bg-blue-600 text-white shadow-sm' : 'bg-blue-50 text-blue-600 hover:bg-blue-100' ?>">Peminjaman Saya</a>
                        
                        <a href="<?= Url::to(['/member/profil']) ?>" class="flex-1 text-center py-2 px-4 rounded-full font-medium text-sm transition-all <?= $route == 'member/profil' ? 'bg-blue-600 text-white shadow-sm' : 'bg-blue-50 text-blue-600 hover:bg-blue-100' ?>">Profil Saya</a>
                    <?php endif; ?>

                </nav>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <main>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
            <?= $content ?>
        </div>
    </main>
</div>

    <!-- Confirmation Modal (global) -->
    <div id="global-confirm-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center px-4 py-6">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" data-action="dismiss"></div>
        <div class="bg-white rounded-lg shadow-xl w-11/12 max-w-lg z-10 max-h-[90vh] overflow-y-auto">
            <div class="p-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Konfirmasi</h3>
            </div>
            <div class="p-4">
                <p id="global-confirm-message" class="text-sm text-gray-700">Pesan konfirmasi...</p>
            </div>
            <div class="flex justify-end gap-3 p-4 border-t">
                <button id="global-confirm-cancel" class="px-4 py-2 rounded-md bg-white border border-gray-200 text-sm text-gray-700 hover:bg-gray-50">Batal</button>
                <button id="global-confirm-ok" class="px-4 py-2 rounded-md bg-blue-600 text-sm text-white hover:bg-blue-700">OK</button>
            </div>
        </div>
    </div>

    <script>
    (function(){
        // Simple Tailwind modal confirm override for yii.confirm
        var modal = document.getElementById('global-confirm-modal');
        var msgEl = document.getElementById('global-confirm-message');
        var okBtn = document.getElementById('global-confirm-ok');
        var cancelBtn = document.getElementById('global-confirm-cancel');
        var backdrop = modal ? modal.querySelector('[data-action="dismiss"]') : null;
        var currentCallback = null;

        function openModal(message, callback) {
            if (!modal) return false;
            msgEl.textContent = message;
            currentCallback = callback || function(){};
            modal.classList.remove('hidden');
            // small delay to allow CSS and focus
            setTimeout(function(){ okBtn.focus(); }, 60);
        }

        function closeModal() {
            if (!modal) return;
            modal.classList.add('hidden');
            currentCallback = null;
        }

        if (okBtn) okBtn.addEventListener('click', function(e){
            e.preventDefault();
            if (typeof currentCallback === 'function') {
                try { currentCallback(true); } catch (err) { console.error(err); }
            }
            closeModal();
        });

        if (cancelBtn) cancelBtn.addEventListener('click', function(e){
            e.preventDefault();
            closeModal();
        });

        if (backdrop) backdrop.addEventListener('click', function(){ closeModal(); });

        document.addEventListener('keydown', function(e){
            if (!modal || modal.classList.contains('hidden')) return;
            if (e.key === 'Escape') { closeModal(); }
            if (e.key === 'Enter') { e.preventDefault(); okBtn.click(); }
        });

        // Override Yii confirm
        if (window.yii) {
            window.yii.confirm = function(message, okCallback) {
                openModal(message, function(accepted){ if (accepted && typeof okCallback === 'function') okCallback(); });
            };
        } else {
            // If yii not loaded yet, attach to window and try again
            window.addEventListener('load', function(){
                if (window.yii) {
                    window.yii.confirm = function(message, okCallback) {
                        openModal(message, function(accepted){ if (accepted && typeof okCallback === 'function') okCallback(); });
                    };
                }
            });
        }
    })();
    </script>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>