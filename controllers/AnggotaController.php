<?php

namespace app\controllers;

use app\models\Anggota;
use app\models\AnggotaSearch;
use app\models\Peminjaman;
use app\models\ItemPeminjaman;
use app\models\Buku;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Yii;

class AnggotaController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['pinjam-online', 'pengembalian-mandiri'],
                'rules' => [
                    [
                        'actions' => ['pinjam-online', 'pengembalian-mandiri'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'pinjam-online' => ['POST'],
                    'pengembalian-mandiri' => ['POST'],
                ],
                'actions' => [
                    'delete' => ['POST'],
                    'pinjam-online' => ['POST'],
                    'pengembalian-mandiri' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new AnggotaSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Anggota();
        $transaction = \Yii::$app->db->beginTransaction();
        
        try {
            $post = \Yii::$app->request->post();
            
            if ($post) {
                $user = null;
                $userid = null;
                
                // Jika userID dipilih dari dropdown
                if (!empty($post['Anggota']['userID'])) {
                    $userid = (int)$post['Anggota']['userID'];
                    $user = \app\models\User::findOne($userid);
                    if (!$user) {
                        throw new \Exception('User yang dipilih tidak ditemukan.');
                    }
                }
                // Jika email diberikan dan userID belum dipilih, buat User baru
                elseif (!empty($post['email'])) {
                    $user = new \app\models\User();
                    $user->email = $post['email'];
                    // Ambil nama dari form, jika tidak ada gunakan email
                    $nama = $post['Anggota']['nama'] ?? $post['email'];
                    $user->nama = $nama;
                    $user->passwordHash = \Yii::$app->security->generatePasswordHash('12345'); // Default password
                    $user->status = 'aktif';
                    $user->tipe_user = 'anggota';
                    
                    // Tambahkan telepon dan alamat ke user jika ada kolomnya
                    if (isset($post['telepon']) && $user->hasAttribute('telepon')) {
                        $user->telepon = $post['telepon'];
                    }
                    if (isset($post['alamat']) && $user->hasAttribute('alamat')) {
                        $user->alamat = $post['alamat'];
                    }
                    
                    if (!$user->save()) {
                        throw new \Exception('Gagal membuat user: ' . implode(', ', $user->getFirstErrors()));
                    }
                    
                    $userid = $user->userid;
                } else {
                    throw new \Exception('Email harus diberikan untuk membuat user baru atau pilih user yang sudah ada.');
                }
                
                // Cek apakah anggota dengan anggotaID = userid sudah ada
                $existingAnggota = Anggota::findOne($userid);
                if ($existingAnggota) {
                    // Jika sudah ada, tidak perlu buat baru
                    $transaction->commit();
                    \Yii::$app->session->setFlash('success', 'Anggota sudah terdaftar untuk user ini.');
                    return $this->redirect(['index']);
                }
                
                // Set anggotaID = userid (karena relasi menggunakan anggotaID = userid)
                // Catatan: Ini hanya bekerja jika anggotaID tidak auto-increment
                // Jika auto-increment, kita perlu pendekatan lain
                try {
                    $model->anggotaID = $userid;
                } catch (\Exception $e) {
                    // Jika anggotaID auto-increment, kita tidak bisa set manual
                    // Dalam kasus ini, simpan dulu lalu update userid di user (jika diperlukan)
                    // Atau gunakan pendekatan lain sesuai struktur database
                }
                
                if ($model->save()) {
                    $transaction->commit();
                    \Yii::$app->session->setFlash('success', 'Anggota berhasil ditambahkan.');
                    return $this->redirect(['index']);
                } else {
                    throw new \Exception('Gagal menyimpan anggota: ' . implode(', ', $model->getFirstErrors()));
                }
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            \Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $transaction = \Yii::$app->db->beginTransaction();
        
        try {
            $post = \Yii::$app->request->post();
            
            if ($post) {
                // Update data user jika ada
                $user = null;
                if ($model->anggotaID) {
                    // Cari user berdasarkan anggotaID (jika relasi menggunakan anggotaID = userid)
                    $user = \app\models\User::findOne($model->anggotaID);
                }
                
                // Update nama, email, telepon, alamat di user
                if (!empty($post['nama']) && $user) {
                    $user->nama = $post['nama'];
                }
                if (!empty($post['email']) && $user) {
                    $user->email = $post['email'];
                }
                if (isset($post['telepon']) && $user && $user->hasAttribute('telepon')) {
                    $user->telepon = $post['telepon'];
                }
                if (isset($post['alamat']) && $user && $user->hasAttribute('alamat')) {
                    $user->alamat = $post['alamat'];
                }
                
                if ($user && !$user->save()) {
                    throw new \Exception('Gagal memperbarui user: ' . implode(', ', $user->getFirstErrors()));
                }
                
                // Update userID jika dipilih
                if (isset($post['Anggota']['userID']) && !empty($post['Anggota']['userID'])) {
                    // Jika userID diubah, perlu update relasi
                    // Tapi karena relasi menggunakan anggotaID = userid, kita tidak bisa mengubahnya
                    // Jadi kita skip perubahan userID
                }
                
                if ($model->save()) {
                    $transaction->commit();
                    \Yii::$app->session->setFlash('success', 'Anggota berhasil diperbarui.');
                    return $this->redirect(['index']);
                } else {
                    throw new \Exception('Gagal memperbarui anggota: ' . implode(', ', $model->getFirstErrors()));
                }
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            \Yii::$app->session->setFlash('error', 'Gagal memperbarui anggota: ' . $e->getMessage());
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $transaction = \Yii::$app->db->beginTransaction();
        
        try {
            // Ambil nama anggota untuk flash message
            $namaAnggota = 'Anggota';
            if ($model->user) {
                $namaAnggota = $model->user->nama ?? $model->user->email ?? 'Anggota';
            }
            
            // Hapus anggota
            $model->delete();
            
            // Catatan: User tidak dihapus karena mungkin masih digunakan untuk login
            // Jika ingin menghapus user juga, uncomment baris berikut:
            // if ($model->user) {
            //     $model->user->delete();
            // }
            
            $transaction->commit();
            \Yii::$app->session->setFlash('success', 'Anggota "' . $namaAnggota . '" berhasil dihapus.');
        } catch (\Exception $e) {
            $transaction->rollBack();
            \Yii::$app->session->setFlash('error', 'Gagal menghapus anggota: ' . $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    /**
     * Action untuk meminjam buku secara online.
     * Method ini akan:
     * 1. Memverifikasi bahwa user adalah Anggota yang sudah login
     * 2. Memverifikasi stok buku tersedia
     * 3. Membuat record baru di tabel Peminjaman dengan status 'menunggu verifikasi admin'
     *
     * @param int $bukuId ID buku yang akan dipinjam
     * @return Response
     */
    public function actionPinjamOnline($bukuId)
    {
        // Verifikasi user sudah login
        if (Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('error', 'Anda harus login terlebih dahulu.');
            return $this->redirect(['site/login']);
        }

        $user = Yii::$app->user->identity;

        // Verifikasi user adalah anggota
        if ($user->tipe_user !== 'anggota') {
            Yii::$app->session->setFlash('error', 'Hanya anggota yang dapat meminjam buku.');
            return $this->goBack();
        }

        // Cari data anggota berdasarkan anggotaID = userid (karena relasi menggunakan anggotaID = userid)
        $anggota = null;
        $userid = $user->userid ?? $user->id ?? null;
        if ($userid) {
            $anggota = Anggota::findOne($userid);
        }
        if (!$anggota) {
            Yii::$app->session->setFlash('error', 'Data anggota tidak ditemukan.');
            return $this->goBack();
        }

        // Verifikasi buku ada dan stok tersedia
        $buku = Buku::findOne($bukuId);
        if (!$buku) {
            Yii::$app->session->setFlash('error', 'Buku tidak ditemukan.');
            return $this->goBack();
        }

        // Cek stok buku
        $stokSaatIni = $buku->stok ?? 0;
        if ($stokSaatIni <= 0) {
            Yii::$app->session->setFlash('error', 'Maaf, stok buku tidak tersedia.');
            return $this->goBack();
        }

        // Mulai transaction
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Buat record Peminjaman baru dengan status 'menunggu_verifikasi_admin'
            $peminjaman = new Peminjaman();
            $peminjaman->anggotaID = $anggota->anggotaID;
            $peminjaman->status = 'menunggu_verifikasi_admin';
            // tanggalPinjam dan tanggalKembali akan diisi saat verifikasi admin

            if (!$peminjaman->save(false)) {
                throw new \Exception('Gagal membuat record peminjaman.');
            }

            // Buat record ItemPeminjaman untuk menghubungkan buku dengan peminjaman
            // Cek apakah tabel item_peminjaman ada
            $itemPeminjaman = null;
            try {
                $tableSchema = \Yii::$app->db->getTableSchema('item_peminjaman');
                if ($tableSchema) {
                    $itemPeminjaman = new ItemPeminjaman();
                    $itemPeminjaman->peminjamanID = $peminjaman->getId();
                    $itemPeminjaman->bukuID = $bukuId;
                    
                    if (!$itemPeminjaman->save()) {
                        throw new \Exception('Gagal membuat item peminjaman: ' . implode(', ', $itemPeminjaman->getFirstErrors()));
                    }
                } else {
                    // Jika tabel tidak ada, skip pembuatan item peminjaman
                    \Yii::$app->session->setFlash('warning', 'Tabel item_peminjaman tidak ditemukan. Item peminjaman tidak dibuat.');
                }
            } catch (\Exception $e) {
                // Jika error, skip pembuatan item peminjaman
                \Yii::$app->session->setFlash('warning', 'Gagal membuat item peminjaman: ' . $e->getMessage());
            }

            // Kurangi stok buku (akan dikembalikan saat admin verifikasi atau saat dikembalikan)
            // Catatan: Stok bisa dikurangi sekarang atau saat verifikasi, tergantung business logic
            // Untuk sekarang, kita kurangi stok saat peminjaman dibuat
            $buku->stok = $stokSaatIni - 1;
            if (!$buku->save(false)) {
                throw new \Exception('Gagal mengurangi stok buku.');
            }

            $transaction->commit();
            
            // Pesan sukses yang lebih informatif
            $judulBuku = $buku->judul ?? 'Buku';
            Yii::$app->session->setFlash('success', 
                "Peminjaman buku <strong>" . Html::encode($judulBuku) . "</strong> berhasil dibuat! " .
                "Status: <strong>Menunggu Verifikasi Admin</strong>. " .
                "Anda akan mendapat notifikasi setelah admin memverifikasi peminjaman Anda."
            );

        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Gagal memproses peminjaman: ' . $e->getMessage());
        }

        // Redirect ke dashboard anggota setelah peminjaman
        return $this->redirect(['site/dashboard-anggota']);
    }

    /**
     * Action untuk pengembalian buku secara mandiri oleh anggota.
     * Method ini akan:
     * 1. Mencari peminjaman berdasarkan ID
     * 2. Memverifikasi bahwa peminjaman tersebut milik anggota yang login
     * 3. Mengisi tglKembali dengan tanggal hari ini
     * 4. Mengubah status menjadi 'dikembalikan'
     * 5. Memberi pesan peringatan bahwa denda (jika ada) akan dibayarkan secara tunai di tempat
     *
     * @param int $peminjamanId ID peminjaman yang akan dikembalikan
     * @return Response
     */
    public function actionPengembalianMandiri($peminjamanId)
    {
        // Verifikasi user sudah login
        if (Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('error', 'Anda harus login terlebih dahulu.');
            return $this->redirect(['site/login']);
        }

        $user = Yii::$app->user->identity;

        // Verifikasi user adalah anggota
        if ($user->tipe_user !== 'anggota') {
            Yii::$app->session->setFlash('error', 'Hanya anggota yang dapat mengembalikan buku.');
            return $this->goBack();
        }

        // Cari data anggota berdasarkan anggotaID = userid (karena relasi menggunakan anggotaID = userid)
        $anggota = null;
        $userid = $user->userid ?? $user->id ?? null;
        if ($userid) {
            $anggota = Anggota::findOne($userid);
        }
        if (!$anggota) {
            Yii::$app->session->setFlash('error', 'Data anggota tidak ditemukan.');
            return $this->goBack();
        }

        // Cari peminjaman dan verifikasi bahwa peminjaman tersebut milik anggota yang login
        $peminjaman = Peminjaman::findOne($peminjamanId);
        if (!$peminjaman) {
            Yii::$app->session->setFlash('error', 'Peminjaman tidak ditemukan.');
            return $this->goBack();
        }

        // Verifikasi bahwa peminjaman tersebut milik anggota yang login
        if ($peminjaman->anggotaID !== $anggota->anggotaID) {
            Yii::$app->session->setFlash('error', 'Anda tidak memiliki akses untuk mengembalikan peminjaman ini.');
            return $this->goBack();
        }

        // Cek apakah sudah dikembalikan
        if ($peminjaman->status === 'dikembalikan') {
            Yii::$app->session->setFlash('info', 'Buku sudah dikembalikan sebelumnya.');
            return $this->goBack();
        }

        // Mulai transaction
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Mengisi tglKembali dengan tanggal hari ini
            $tanggalKembali = date('Y-m-d');
            // Cek kolom yang ada di database (sesuai schema: tglKembali)
            if ($peminjaman->hasAttribute('tglKembali')) {
                $peminjaman->tglKembali = $tanggalKembali;
            } elseif ($peminjaman->hasAttribute('tanggalKembali')) {
                $peminjaman->tanggalKembali = $tanggalKembali;
            } elseif ($peminjaman->hasAttribute('tanggal_kembali')) {
                $peminjaman->tanggal_kembali = $tanggalKembali;
            }

            // Mengubah status menjadi 'dikembalikan'
            $peminjaman->status = 'dikembalikan';

            if (!$peminjaman->save(false)) {
                $errors = $peminjaman->getFirstErrors();
                throw new \Exception('Gagal menyimpan data pengembalian: ' . implode(', ', $errors));
            }

            // Cek apakah ada denda (dengan memanggil hitungDenda untuk mengecek)
            // Gunakan format datetime untuk hitungDenda
            $tanggalKembaliForDenda = date('Y-m-d H:i:s');
            $dendaId = $peminjaman->hitungDenda($tanggalKembaliForDenda, 500);
            $adaDenda = $dendaId !== null;

            // Tambahkan stok buku kembali
            $peminjamanId = $peminjaman->getId();
            try {
                $tableSchema = \Yii::$app->db->getTableSchema('item_peminjaman');
                if ($tableSchema) {
                    $itemPeminjamans = \app\models\ItemPeminjaman::find()
                        ->where(['peminjamanID' => $peminjamanId])
                        ->all();

                    foreach ($itemPeminjamans as $item) {
                        $buku = \app\models\Buku::findOne($item->bukuID);
                        if ($buku) {
                            $stokSaatIni = $buku->stok ?? 0;
                            $buku->stok = $stokSaatIni + 1;
                            
                            if (!$buku->save(false)) {
                                throw new \Exception('Gagal menambah stok buku: ' . ($buku->judul ?? 'Unknown'));
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                // Log error tapi jangan gagalkan transaksi
                \Yii::warning('Gagal menambah stok buku: ' . $e->getMessage(), 'anggota');
            }

            $transaction->commit();

            // Beri pesan peringatan tentang denda jika ada
            if ($adaDenda) {
                Yii::$app->session->setFlash('warning', 'Pengembalian berhasil. Peringatan: Denda akan dibayarkan secara tunai di tempat saat pengembalian buku.');
            } else {
                Yii::$app->session->setFlash('success', 'Pengembalian buku berhasil diproses.');
            }

        } catch (\Exception $e) {
            $transaction->rollBack();
            \Yii::error('Gagal memproses pengembalian mandiri: ' . $e->getMessage(), 'anggota');
            Yii::$app->session->setFlash('error', 'Gagal memproses pengembalian: ' . $e->getMessage());
        }

        return $this->goBack();
    }

    protected function findModel($id): Anggota
    {
        if (($model = Anggota::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

