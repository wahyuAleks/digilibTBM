<?php

namespace app\controllers;

use app\models\LoginForm;
use app\models\ContactForm;
use app\models\RegistrasiForm;
use app\models\Buku;
use app\models\Anggota;
use app\models\Peminjaman;
use app\models\Denda;
use app\models\Kategori;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

class SiteController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'dashboard', 'dashboard-anggota'],
                'rules' => [
                    [
                        'actions' => ['logout', 'dashboard', 'dashboard-anggota'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions(): array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        // Jika user sudah login, redirect berdasarkan role
        if (!Yii::$app->user->isGuest) {
            $user = Yii::$app->user->identity;
            if ($user->tipe_user === 'admin') {
                return $this->redirect(['site/dashboard']);
            } elseif ($user->tipe_user === 'anggota') {
                return $this->redirect(['member/index']);
            }
        }

        // Untuk guest: Tampilkan Katalog Publik
        // Terima keyword dari parameter 'keyword' atau 'search' untuk kompatibilitas view yang berbeda
        $keyword = Yii::$app->request->get('keyword', Yii::$app->request->get('search', ''));
        $kategoriId = Yii::$app->request->get('kategori', '');

        // Query untuk buku - urutkan berdasarkan ID terbaru sehingga sama seperti pada portal anggota
        $query = Buku::find()->orderBy(['bukuID' => SORT_DESC]);

        // Filter berdasarkan keyword (judul, penulis, dan ISBN)
        if (!empty($keyword)) {
            $query->andWhere(['or',
                ['like', 'judul', $keyword],
                ['like', 'penulis', $keyword],
                ['like', 'isbn', $keyword],
            ]);
        }

        // Filter berdasarkan kategori
        if (!empty($kategoriId)) {
            $query->andWhere(['kategoriID' => $kategoriId]);
        }

        // Ambil semua buku (atau bisa dibatasi jika perlu)
        $bukuList = $query->all();

        // Ambil semua kategori untuk dropdown
        $kategoriList = Kategori::find()->all();

        return $this->render('index', [
            'bukuList' => $bukuList,
            'kategoriList' => $kategoriList,
            'keyword' => $keyword,
            'kategoriId' => $kategoriId,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        // 1. Cek jika user sudah login sebelumnya
        if (!Yii::$app->user->isGuest) {
            return $this->redirectBasedOnRole();
        }

        $model = new LoginForm();
        
        // 2. Proses Login jika ada post request
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirectBasedOnRole();
        }

        $model->password = '';
        $this->layout = false; // Matikan layout karena halaman login punya struktur sendiri
        
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     */
    public function actionLogout(): Response
    {
        Yii::$app->user->logout();
        return $this->redirect(['site/login']);
    }

    /**
     * Helper function: Redirect user based on their tipe_user.
     */
    protected function redirectBasedOnRole()
    {
        $user = Yii::$app->user->identity;

        // Jika Admin -> Ke Dashboard Admin (site/index)
        if ($user->tipe_user === 'admin') {
            return $this->redirect(['site/dashboard']);
        }

        // Jika Anggota -> Ke Dashboard Member (member/index)
        if ($user->tipe_user === 'anggota') {
            return $this->redirect(['member/index']);
        }

        // Default fallback
        return $this->goHome();
    }

    /**
     * Displays Member/Anggota Dashboard.
     *
     * @return string
     */
    public function actionDashboardAnggota()
    {
        // Pastikan hanya anggota yang bisa akses
        if (Yii::$app->user->isGuest || Yii::$app->user->identity->tipe_user !== 'anggota') {
            return $this->redirect(['member/index']);
        }

        $user = Yii::$app->user->identity;
        
        // Cari anggota berdasarkan anggotaID = userid
        $anggota = null;
        $userid = $user->userid ?? $user->id ?? null;
        if ($userid) {
            $anggota = Anggota::findOne($userid);
        }
        
        // Inisialisasi statistik
        $totalPeminjaman = 0;
        $peminjamanAktif = 0;
        $peminjamanSelesai = 0;
        $peminjamanTerlambat = 0;
        $menungguVerifikasi = 0;
        $totalDenda = 0;
        $peminjamanTerbaru = [];
        
        if ($anggota) {
            $anggotaID = $anggota->anggotaID;
            
            // Total peminjaman
            $totalPeminjaman = Peminjaman::find()
                ->where(['anggotaID' => $anggotaID])
                ->count();
            
            // Peminjaman aktif (status dipinjam atau menunggu verifikasi)
            $peminjamanAktif = Peminjaman::find()
                ->where(['anggotaID' => $anggotaID])
                ->andWhere(['in', 'status', ['dipinjam', 'menunggu_verifikasi_admin']])
                ->count();
            
            // Peminjaman selesai (status dikembalikan)
            $peminjamanSelesai = Peminjaman::find()
                ->where(['anggotaID' => $anggotaID])
                ->andWhere(['status' => 'dikembalikan'])
                ->count();
            
            // Peminjaman terlambat (status dipinjam dan tglJatuhTempo sudah lewat)
            try {
                $tableSchema = \Yii::$app->db->getTableSchema('peminjaman');
                if ($tableSchema) {
                    $columns = array_keys($tableSchema->columns);
                    if (in_array('tglJatuhTempo', $columns)) {
                        $peminjamanTerlambat = Peminjaman::find()
                            ->where(['anggotaID' => $anggotaID])
                            ->andWhere(['status' => 'dipinjam'])
                            ->andWhere(['<', 'tglJatuhTempo', date('Y-m-d')])
                            ->count();
                    }
                }
            } catch (\Exception $e) {
                $peminjamanTerlambat = 0;
            }
            
            // Menunggu verifikasi
            $menungguVerifikasi = Peminjaman::find()
                ->where(['anggotaID' => $anggotaID])
                ->andWhere(['status' => 'menunggu_verifikasi_admin'])
                ->count();
            
            // Hitung total denda untuk anggota ini
            try {
                $tableSchema = \Yii::$app->db->getTableSchema('denda');
                if ($tableSchema) {
                    $columns = array_keys($tableSchema->columns);
                    $dendaKey = null;
                    
                    // Cari kolom untuk foreign key peminjaman
                    if (in_array('peminjamanID', $columns)) {
                        $dendaKey = 'peminjamanID';
                    } elseif (in_array('peminjaman_id', $columns)) {
                        $dendaKey = 'peminjaman_id';
                    }
                    
                    if ($dendaKey) {
                        // Ambil semua peminjaman anggota
                        $peminjamanIds = Peminjaman::find()
                            ->select(Peminjaman::primaryKey()[0] ?? 'id')
                            ->where(['anggotaID' => $anggotaID])
                            ->column();
                        
                        if (!empty($peminjamanIds)) {
                            // Cari kolom jumlah
                            if (in_array('jumlah', $columns)) {
                                $totalDenda = (float)(\app\models\Denda::find()
                                    ->where([$dendaKey => $peminjamanIds])
                                    ->sum('jumlah') ?? 0);
                            } elseif (in_array('total', $columns)) {
                                $totalDenda = (float)(\app\models\Denda::find()
                                    ->where([$dendaKey => $peminjamanIds])
                                    ->sum('total') ?? 0);
                            } elseif (in_array('nominal', $columns)) {
                                $totalDenda = (float)(\app\models\Denda::find()
                                    ->where([$dendaKey => $peminjamanIds])
                                    ->sum('nominal') ?? 0);
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                $totalDenda = 0;
            }
            
            // Ambil 5 peminjaman terbaru
            try {
                $tableSchema = \Yii::$app->db->getTableSchema('peminjaman');
                if ($tableSchema) {
                    $columns = array_keys($tableSchema->columns);
                    $primaryKey = Peminjaman::primaryKey();
                    $orderBy = !empty($primaryKey) ? $primaryKey[0] : null;
                    
                    if (!$orderBy || !in_array($orderBy, $columns)) {
                        if (in_array('tanggalPinjam', $columns)) {
                            $orderBy = 'tanggalPinjam';
                        } elseif (in_array('tglPinjam', $columns)) {
                            $orderBy = 'tglPinjam';
                        } elseif (in_array('anggotaID', $columns)) {
                            $orderBy = 'anggotaID';
                        } else {
                            $orderBy = null;
                        }
                    }
                    
                    // Cek apakah tabel item_peminjaman ada
                    $withRelations = [];
                    try {
                        $itemPeminjamanSchema = \Yii::$app->db->getTableSchema('item_peminjaman');
                        if ($itemPeminjamanSchema) {
                            $withRelations[] = 'itemPeminjamans.buku';
                        }
                    } catch (\Exception $e) {
                        // Skip jika tabel tidak ada
                    }
                    
                    $query = Peminjaman::find()
                        ->where(['anggotaID' => $anggotaID]);
                    
                    if (!empty($withRelations)) {
                        $query->with($withRelations);
                    }
                    
                    if ($orderBy) {
                        $query->orderBy([$orderBy => SORT_DESC]);
                    }
                    
                    $peminjamanTerbaru = $query->limit(5)->all();
                }
            } catch (\Exception $e) {
                // Fallback
                $peminjamanTerbaru = Peminjaman::find()
                    ->where(['anggotaID' => $anggotaID])
                    ->limit(5)
                    ->all();
            }
        }
        
        return $this->render('dashboard-anggota', [
            'totalPeminjaman' => $totalPeminjaman,
            'peminjamanAktif' => $peminjamanAktif,
            'peminjamanSelesai' => $peminjamanSelesai,
            'peminjamanTerlambat' => $peminjamanTerlambat,
            'menungguVerifikasi' => $menungguVerifikasi,
            'totalDenda' => $totalDenda,
            'peminjamanTerbaru' => $peminjamanTerbaru,
            'anggota' => $anggota,
        ]);
    }

    /**
     * Displays Admin Dashboard.
     *
     * @return string
     */
    public function actionDashboard()
    {
        // Pastikan hanya admin yang bisa akses
        if (Yii::$app->user->isGuest || Yii::$app->user->identity->tipe_user !== 'admin') {
            return $this->redirect(['site/index']);
        }

        // Hitung statistik
        $totalBuku = Buku::find()->count();
        // Hitung total stok buku yang tersedia (stok > 0)
        $bukuTersedia = (int)(Buku::find()->where(['>', 'stok', 0])->sum('stok') ?? 0);
        
        $totalAnggota = Anggota::find()->count();
        // Anggota aktif adalah semua anggota yang ada (karena mereka sudah terdaftar)
        $anggotaAktif = $totalAnggota;
        
        $peminjamanAktif = Peminjaman::find()
            ->where(['in', 'status', ['dipinjam', 'menunggu_verifikasi_admin']])
            ->count();
        
        // Hitung total denda - cek apakah kolom jumlah ada
        $totalDenda = 0;
        try {
            // Cek apakah tabel denda ada dan kolom jumlah ada
            $tableSchema = \Yii::$app->db->getTableSchema('denda');
            if ($tableSchema) {
                $columns = array_keys($tableSchema->columns);
                if (in_array('jumlah', $columns)) {
                    $totalDenda = (float)(Denda::find()->sum('jumlah') ?? 0);
                } elseif (in_array('total', $columns)) {
                    // Coba kolom alternatif 'total'
                    $totalDenda = (float)(Denda::find()->sum('total') ?? 0);
                } elseif (in_array('nominal', $columns)) {
                    // Coba kolom alternatif 'nominal'
                    $totalDenda = (float)(Denda::find()->sum('nominal') ?? 0);
                }
            }
        } catch (\Exception $e) {
            // Jika error, set totalDenda = 0
            $totalDenda = 0;
        }

        // Hitung buku terlambat: status dipinjam dan tglJatuhTempo sudah lewat
        $bukuTerlambat = 0;
        try {
            // Cek apakah kolom tglJatuhTempo ada
            $tableSchema = \Yii::$app->db->getTableSchema('peminjaman');
            if ($tableSchema) {
                $columns = array_keys($tableSchema->columns);
                if (in_array('tglJatuhTempo', $columns)) {
                    $bukuTerlambat = Peminjaman::find()
                        ->where(['status' => 'dipinjam'])
                        ->andWhere(['<', 'tglJatuhTempo', date('Y-m-d')])
                        ->count();
                }
            }
        } catch (\Exception $e) {
            // Jika error, set bukuTerlambat = 0
            $bukuTerlambat = 0;
        }

        // Ambil 5 peminjaman terbaru - cek kolom yang ada untuk sorting
        $peminjamanTerbaru = [];
        try {
            $tableSchema = \Yii::$app->db->getTableSchema('peminjaman');
            if ($tableSchema) {
                $columns = array_keys($tableSchema->columns);
                
                // Cek primary key dari model Peminjaman
                $primaryKey = Peminjaman::primaryKey();
                $orderBy = !empty($primaryKey) ? $primaryKey[0] : null;

                // Jika primary key tidak ada di kolom, cari alternatif
                if (!$orderBy || !in_array($orderBy, $columns)) {
                    // Cek kolom tanggal yang ada
                    if (in_array('tanggalPinjam', $columns)) {
                        $orderBy = 'tanggalPinjam';
                    } elseif (in_array('tglPinjam', $columns)) {
                        $orderBy = 'tglPinjam';
                    } elseif (in_array('anggotaID', $columns)) { // Fallback ke anggotaID jika tidak ada tanggal
                        $orderBy = 'anggotaID';
                    } else {
                        $orderBy = null; // Tidak ada kolom yang cocok untuk order
                    }
                }
                
                // Cek apakah tabel item_peminjaman ada sebelum eager loading
                $withRelations = ['anggota.user'];
                try {
                    $itemPeminjamanSchema = \Yii::$app->db->getTableSchema('item_peminjaman');
                    if ($itemPeminjamanSchema) {
                        $withRelations[] = 'itemPeminjamans.buku';
                    }
                } catch (\Exception $e) {
                    // Tabel tidak ada, skip eager loading itemPeminjamans
                }
                
                if ($orderBy) {
                    $peminjamanTerbaru = Peminjaman::find()
                        ->with($withRelations)
                        ->orderBy([$orderBy => SORT_DESC])
                        ->limit(5)
                        ->all();
                } else {
                    // Jika tidak ada kolom untuk order, ambil tanpa order
                    $peminjamanTerbaru = Peminjaman::find()
                        ->with($withRelations)
                        ->limit(5)
                        ->all();
                }
            } else {
                // Fallback: gunakan primary key dari model
                $primaryKey = Peminjaman::primaryKey();
                $orderBy = !empty($primaryKey) ? $primaryKey[0] : 'anggotaID';
                
                // Cek apakah tabel item_peminjaman ada
                $withRelations = ['anggota.user'];
                try {
                    $itemPeminjamanSchema = \Yii::$app->db->getTableSchema('item_peminjaman');
                    if ($itemPeminjamanSchema) {
                        $withRelations[] = 'itemPeminjamans.buku';
                    }
                } catch (\Exception $e) {
                    // Tabel tidak ada, skip
                }
                
                $peminjamanTerbaru = Peminjaman::find()
                    ->with($withRelations)
                    ->orderBy([$orderBy => SORT_DESC])
                    ->limit(5)
                    ->all();
            }
        } catch (\Exception $e) {
            // Jika error, gunakan primary key dari model atau anggotaID
            try {
                $primaryKey = Peminjaman::primaryKey();
                $orderBy = !empty($primaryKey) ? $primaryKey[0] : 'anggotaID';
                
                // Cek apakah tabel item_peminjaman ada
                $withRelations = ['anggota.user'];
                try {
                    $itemPeminjamanSchema = \Yii::$app->db->getTableSchema('item_peminjaman');
                    if ($itemPeminjamanSchema) {
                        $withRelations[] = 'itemPeminjamans.buku';
                    }
                } catch (\Exception $e3) {
                    // Tabel tidak ada, skip
                }
                
                $peminjamanTerbaru = Peminjaman::find()
                    ->with($withRelations)
                    ->orderBy([$orderBy => SORT_DESC])
                    ->limit(5)
                    ->all();
            } catch (\Exception $e2) {
                // Jika masih error, ambil tanpa order
                $withRelations = ['anggota.user'];
                try {
                    $itemPeminjamanSchema = \Yii::$app->db->getTableSchema('item_peminjaman');
                    if ($itemPeminjamanSchema) {
                        $withRelations[] = 'itemPeminjamans.buku';
                    }
                } catch (\Exception $e4) {
                    // Tabel tidak ada, skip
                }
                
                $peminjamanTerbaru = Peminjaman::find()
                    ->with($withRelations)
                    ->limit(5)
                    ->all();
            }
        }
        
        // Cek buku dengan stok rendah (stok <= 2)
        $bukuStokRendah = Buku::find()
            ->where(['<=', 'stok', 2])
            ->count();
        
        // Hitung pinjaman yang menunggu verifikasi admin
        $menungguVerifikasi = Peminjaman::find()
            ->where(['status' => 'menunggu_verifikasi_admin'])
            ->count();

        return $this->render('dashboard-admin', [
            'totalBuku' => $totalBuku,
            'bukuTersedia' => $bukuTersedia,
            'totalAnggota' => $totalAnggota,
            'anggotaAktif' => $anggotaAktif,
            'peminjamanAktif' => $peminjamanAktif,
            'totalDenda' => $totalDenda,
            'bukuTerlambat' => $bukuTerlambat,
            'peminjamanTerbaru' => $peminjamanTerbaru,
            'bukuStokRendah' => $bukuStokRendah,
            'menungguVerifikasi' => $menungguVerifikasi,
        ]);
    }

    /**
     * Action untuk membuat tabel item_peminjaman secara otomatis
     * Hanya bisa diakses oleh admin
     */
    public function actionCreateItemPeminjamanTable()
    {
        // Pastikan hanya admin yang bisa akses
        if (Yii::$app->user->isGuest || Yii::$app->user->identity->tipe_user !== 'admin') {
            Yii::$app->session->setFlash('error', 'Anda tidak memiliki akses untuk melakukan tindakan ini.');
            return $this->redirect(['site/index']);
        }

        try {
            $db = \Yii::$app->db;
            
            // Cek apakah tabel sudah ada
            $tableSchema = $db->getTableSchema('item_peminjaman');
            if ($tableSchema) {
                Yii::$app->session->setFlash('success', 'Tabel item_peminjaman sudah ada di database.');
                return $this->redirect(['site/dashboard']);
            }
            
            // Cek primary key tabel peminjaman
            $peminjamanSchema = $db->getTableSchema('peminjaman');
            $peminjamanPK = 'id';
            if ($peminjamanSchema) {
                $pks = $peminjamanSchema->primaryKey;
                if (!empty($pks)) {
                    $peminjamanPK = $pks[0];
                }
            }
            
            // Cek primary key tabel buku
            $bukuSchema = $db->getTableSchema('buku');
            $bukuPK = 'bukuID';
            if ($bukuSchema) {
                $pks = $bukuSchema->primaryKey;
                if (!empty($pks)) {
                    $bukuPK = $pks[0];
                }
            }
            
            // Buat tabel tanpa foreign key constraint dulu (untuk menghindari error jika primary key berbeda)
            $sql = "CREATE TABLE IF NOT EXISTS `item_peminjaman` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `peminjamanID` int(11) NOT NULL,
              `bukuID` int(11) NOT NULL,
              PRIMARY KEY (`id`),
              KEY `idx_peminjamanID` (`peminjamanID`),
              KEY `idx_bukuID` (`bukuID`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            
            $db->createCommand($sql)->execute();
            
            Yii::$app->session->setFlash('success', 'Tabel item_peminjaman berhasil dibuat! Primary key peminjaman: ' . $peminjamanPK . ', Primary key buku: ' . $bukuPK);
            
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Gagal membuat tabel item_peminjaman: ' . $e->getMessage());
        }

        return $this->redirect(['site/dashboard']);
    }

    /**
     * Registration action.
     */
    public function actionRegister()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new RegistrasiForm();
        if ($model->load(Yii::$app->request->post()) && $model->register()) {
            Yii::$app->session->setFlash('success', 'Registrasi berhasil! Silakan login.');
            return $this->redirect(['site/login']);
        }

        $this->layout = false; // Matikan layout

        return $this->render('register', [
            'model' => $model,
        ]);
    }

    // --- Action Tambahan (Contact & About) ---

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');
            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
}
