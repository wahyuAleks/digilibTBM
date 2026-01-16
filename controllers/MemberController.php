<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\models\Buku; // <--- Pastikan baris ini ada biar bisa panggil Model Buku

class MemberController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $user = Yii::$app->user->identity;
        
        // Cari data anggota berdasarkan anggotaID = userid (karena relasi menggunakan anggotaID = userid)
        $anggota = null;
        $userid = $user->userid ?? $user->id ?? null;
        if ($userid) {
            $anggota = \app\models\Anggota::findOne($userid);
        }
        
        // Hitung statistik peminjaman
        $peminjamanAktif = 0;
        $peminjamanTerlambat = 0;
        $totalDenda = 0;
        
        if ($anggota) {
            $anggotaID = $anggota->anggotaID;
            
            // Peminjaman aktif (status dipinjam atau menunggu verifikasi)
            $peminjamanAktif = \app\models\Peminjaman::find()
                ->where(['anggotaID' => $anggotaID])
                ->andWhere(['in', 'status', ['dipinjam', 'menunggu_verifikasi_admin']])
                ->count();
            
            // Peminjaman terlambat (status dipinjam dan tglJatuhTempo sudah lewat)
            $peminjamanTerlambat = \app\models\Peminjaman::find()
                ->where(['anggotaID' => $anggotaID])
                ->andWhere(['status' => 'dipinjam'])
                ->andWhere(['<', 'tglJatuhTempo', date('Y-m-d')])
                ->count();
            
            // Hitung total denda - cek apakah tabel denda ada
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
                        $peminjamanIds = \app\models\Peminjaman::find()
                            ->select(\app\models\Peminjaman::primaryKey()[0] ?? 'id')
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
                // Skip jika error
                $totalDenda = 0;
            }
        }
        
        
        // 1. AMBIL DATA DARI DATABASE
        // Kita cari semua buku, urutkan dari yang paling baru (ID desc)
        // Eager load kategori untuk performa lebih baik
        $query = Buku::find()
            ->with('kategori')
            ->orderBy(['bukuID' => SORT_DESC]);
        
        // 2. FILTER BERDASARKAN SEARCH (jika ada parameter search di URL)
        $searchTerm = Yii::$app->request->get('search');
        if (!empty($searchTerm)) {
            // Search di kolom judul, penulis, dan ISBN
            $query->andFilterWhere(['or',
                ['like', 'judul', $searchTerm],
                ['like', 'penulis', $searchTerm],
                ['like', 'isbn', $searchTerm],
            ]);
        }
        
        $bukuList = $query->all();

        // 2. KIRIM DATA KE VIEW (PENTING!)
        // Perhatikan bagian array ['bukuList' => $bukuList]
        // Ini yang bikin variabel $bukuList dikenali di file index.php
        return $this->render('index', [
            'bukuList' => $bukuList,
            'peminjamanAktif' => $peminjamanAktif,
            'peminjamanTerlambat' => $peminjamanTerlambat,
            'totalDenda' => $totalDenda,
        ]);
    }

    public function actionPeminjaman()
    {
        $user = Yii::$app->user->identity;
        
        // Cari data anggota berdasarkan anggotaID = userid (karena relasi menggunakan anggotaID = userid)
        $anggota = null;
        $userid = $user->userid ?? $user->id ?? null;
        if ($userid) {
            $anggota = \app\models\Anggota::findOne($userid);
        }
        
        if (!$anggota) {
            Yii::$app->session->setFlash('error', 'Data anggota tidak ditemukan.');
            return $this->redirect(['index']);
        }
        
        // Hitung peminjaman yang menunggu verifikasi untuk menentukan default tab
        $menungguVerifikasiCount = \app\models\Peminjaman::find()
            ->where(['anggotaID' => $anggota->anggotaID])
            ->andWhere(['status' => 'menunggu_verifikasi_admin'])
            ->count();
        
        // Ambil tab aktif dari query parameter, default ke 'menunggu' jika ada yang menunggu verifikasi
        $activeTab = Yii::$app->request->get('tab', ($menungguVerifikasiCount > 0 ? 'menunggu' : 'aktif'));
        
        // Query peminjaman berdasarkan anggota
        // Cek apakah tabel item_peminjaman ada sebelum eager loading
        $withRelations = [];
        $itemPeminjamanTableExists = false;
        try {
            $itemPeminjamanSchema = \Yii::$app->db->getTableSchema('item_peminjaman');
            $itemPeminjamanTableExists = $itemPeminjamanSchema !== null;
            if ($itemPeminjamanTableExists) {
                $withRelations[] = 'itemPeminjamans.buku';
            }
        } catch (\Exception $e) {
            // Tabel tidak ada, skip eager loading itemPeminjamans
            $itemPeminjamanTableExists = false;
        }
        
        // Tentukan orderBy berdasarkan primary key
        $primaryKey = \app\models\Peminjaman::primaryKey();
        $orderBy = !empty($primaryKey) ? $primaryKey[0] : 'anggotaID';
        
        $query = \app\models\Peminjaman::find()
            ->where(['anggotaID' => $anggota->anggotaID]);
        
        // PENTING: Pastikan eager loading dilakukan dengan benar
        if (!empty($withRelations) && $itemPeminjamanTableExists) {
            $query->with($withRelations);
        }
        
        $query->orderBy([$orderBy => SORT_DESC]);
        
        // Filter berdasarkan tab
        if ($activeTab === 'aktif') {
            // Tab aktif: hanya status dipinjam (tidak termasuk menunggu verifikasi)
            $query->andWhere(['status' => 'dipinjam']);
        } elseif ($activeTab === 'menunggu') {
            // Tab menunggu: hanya status menunggu_verifikasi_admin
            $query->andWhere(['status' => 'menunggu_verifikasi_admin']);
        } elseif ($activeTab === 'riwayat') {
            $query->andWhere(['status' => 'dikembalikan']);
        }
        
        $peminjamans = $query->all();
        
        // Hitung statistik
        $totalPeminjaman = \app\models\Peminjaman::find()
            ->where(['anggotaID' => $anggota->anggotaID])
            ->count();
        
        $sedangDipinjam = \app\models\Peminjaman::find()
            ->where(['anggotaID' => $anggota->anggotaID])
            ->andWhere(['status' => 'dipinjam'])
            ->count();
        
        $menungguVerifikasi = \app\models\Peminjaman::find()
            ->where(['anggotaID' => $anggota->anggotaID])
            ->andWhere(['status' => 'menunggu_verifikasi_admin'])
            ->count();
        
        $sudahDikembalikan = \app\models\Peminjaman::find()
            ->where(['anggotaID' => $anggota->anggotaID])
            ->andWhere(['status' => 'dikembalikan'])
            ->count();
        
        return $this->render('peminjaman', [
            'peminjamans' => $peminjamans,
            'totalPeminjaman' => $totalPeminjaman,
            'sedangDipinjam' => $sedangDipinjam,
            'menungguVerifikasi' => $menungguVerifikasi,
            'sudahDikembalikan' => $sudahDikembalikan,
            'activeTab' => $activeTab,
        ]);
    }

    public function actionProfil()
    {
        $user = Yii::$app->user->identity;
        return $this->render('profil', ['user' => $user]);
    }

    public function actionUpdateProfil()
    {
        $user = Yii::$app->user->identity;

        if (Yii::$app->request->isPost) {
            $nama = Yii::$app->request->post('nama');
            $email = Yii::$app->request->post('email');

            // Validasi
            if (empty($nama)) {
                Yii::$app->session->setFlash('error', 'Nama tidak boleh kosong.');
                return $this->redirect(['profil']);
            }

            // Update data user
            $user->nama = $nama;
            
            // Validasi email jika diubah
            if ($email !== $user->email) {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    Yii::$app->session->setFlash('error', 'Format email tidak valid.');
                    return $this->redirect(['profil']);
                }
                
                // Cek apakah email sudah digunakan user lain
                $existingUser = \app\models\User::find()
                    ->where(['email' => $email])
                    ->andWhere(['!=', 'userid', $user->userid])
                    ->one();
                
                if ($existingUser) {
                    Yii::$app->session->setFlash('error', 'Email sudah digunakan oleh user lain.');
                    return $this->redirect(['profil']);
                }
                
                $user->email = $email;
            }

            if ($user->save(false)) {
                Yii::$app->session->setFlash('success', 'Profil berhasil diupdate.');
            } else {
                Yii::$app->session->setFlash('error', 'Gagal mengupdate profil.');
            }

            return $this->redirect(['profil']);
        }

        return $this->render('update-profil', ['user' => $user]);
    }
}