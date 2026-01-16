<?php

namespace app\controllers;

use app\models\Peminjaman;
use app\models\PeminjamanSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\data\ActiveDataProvider;

class PeminjamanController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return !\Yii::$app->user->isGuest && \Yii::$app->user->identity->tipe_user === 'admin';
                        },
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new PeminjamanSearch();
        $params = \Yii::$app->request->queryParams;
        
        // Hitung pinjaman yang menunggu verifikasi untuk menentukan default tab
        $menungguVerifikasiCount = Peminjaman::find()
            ->where(['status' => 'menunggu_verifikasi_admin'])
            ->count();
        
        // Filter berdasarkan tab
        $activeTab = $params['tab'] ?? ($menungguVerifikasiCount > 0 ? 'menunggu' : 'aktif');
        
        // PENTING: Set status SEBELUM memanggil search() agar filter diterapkan dengan benar
        if ($activeTab === 'menunggu') {
            // Tab khusus untuk pinjaman yang menunggu verifikasi
            $searchModel->status = 'menunggu_verifikasi_admin';
            // Juga set di params untuk memastikan filter diterapkan
            $params['PeminjamanSearch']['status'] = 'menunggu_verifikasi_admin';
        } elseif ($activeTab === 'aktif') {
            // Tab aktif menampilkan semua peminjaman aktif termasuk yang menunggu verifikasi
            $searchModel->status = 'dipinjam'; // Akan difilter di search model untuk menampilkan dipinjam dan menunggu verifikasi
            $params['PeminjamanSearch']['status'] = 'dipinjam';
        } elseif ($activeTab === 'terlambat') {
            $searchModel->status = 'dipinjam';
            $params['PeminjamanSearch']['status'] = 'dipinjam';
        } elseif ($activeTab === 'riwayat') {
            $searchModel->status = 'dikembalikan';
            $params['PeminjamanSearch']['status'] = 'dikembalikan';
        }
        
        // Untuk tab menunggu, buat query langsung tanpa search model untuk memastikan filter benar
        if ($activeTab === 'menunggu') {
            // Buat query langsung untuk memastikan filter diterapkan dengan benar
            $query = Peminjaman::find()
                ->where(['status' => 'menunggu_verifikasi_admin'])
                ->with(['anggota.user']);
            
            // Cek apakah tabel item_peminjaman ada untuk eager loading
            try {
                $itemPeminjamanSchema = \Yii::$app->db->getTableSchema('item_peminjaman');
                if ($itemPeminjamanSchema) {
                    $query->with('itemPeminjamans.buku');
                }
            } catch (\Exception $e) {
                // Skip jika tabel tidak ada
            }
            
            // Tentukan orderBy
            $primaryKey = Peminjaman::primaryKey();
            $orderBy = !empty($primaryKey) ? $primaryKey[0] : 'anggotaID';
            $query->orderBy([$orderBy => SORT_DESC]);
            
            // Buat dataProvider langsung
            $dataProvider = new \yii\data\ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]);
            
            // Debug: Log query dan jumlah data (hanya aktif di DEV)
            if (YII_DEBUG) {
                \Yii::info('Query untuk tab menunggu: ' . $query->createCommand()->getRawSql(), 'peminjaman');
                $countBeforePagination = $query->count();
                \Yii::info("Jumlah peminjaman sebelum pagination untuk tab menunggu: $countBeforePagination", 'peminjaman');
            } else {
                $countBeforePagination = $query->count();
            }
        } else {
            // Untuk tab lainnya, gunakan search model
            $dataProvider = $searchModel->search($params);
        }
        
        // Untuk tab menunggu, filter sudah diterapkan di query di atas
        // Untuk tab lainnya, terapkan filter tambahan jika perlu
        if ($activeTab !== 'menunggu') {
            // Untuk tab terlambat, filter tambahan di query
            if ($activeTab === 'terlambat') {
                $dataProvider->query->andWhere(['<', 'tglJatuhTempo', date('Y-m-d')]);
            }
            
            // Untuk tab aktif, pastikan menampilkan juga yang menunggu verifikasi
            if ($activeTab === 'aktif') {
                // Pastikan query menampilkan status 'dipinjam' dan 'menunggu_verifikasi_admin'
                $dataProvider->query->andWhere(['in', 'status', ['dipinjam', 'menunggu_verifikasi_admin']]);
            }
        }
        
        // Debug: Hitung ulang jumlah setelah filter diterapkan
        // Uncomment untuk debug
        // $countAfterFilter = $dataProvider->query->count();
        // \Yii::info("Jumlah peminjaman setelah filter untuk tab '$activeTab': $countAfterFilter", 'peminjaman');

        // Hitung statistik
        $sedangDipinjam = Peminjaman::find()
            ->where(['in', 'status', ['dipinjam', 'menunggu_verifikasi_admin']])
            ->count();

        // Hitung terlambat: status dipinjam dan tglJatuhTempo sudah lewat
        $terlambat = Peminjaman::find()
            ->where(['status' => 'dipinjam'])
            ->andWhere(['<', 'tglJatuhTempo', date('Y-m-d')])
            ->count();

        $sudahDikembalikan = Peminjaman::find()
            ->where(['status' => 'dikembalikan'])
            ->count();
        
        // Hitung pinjaman yang menunggu verifikasi (gunakan yang sudah dihitung sebelumnya)
        $menungguVerifikasi = $menungguVerifikasiCount;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'sedangDipinjam' => $sedangDipinjam,
            'terlambat' => $terlambat,
            'sudahDikembalikan' => $sudahDikembalikan,
            'menungguVerifikasi' => $menungguVerifikasi,
            'activeTab' => $activeTab,
        ]);
    }
}