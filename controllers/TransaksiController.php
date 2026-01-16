<?php

namespace app\controllers;

use app\models\Peminjaman;
use app\models\ItemPeminjaman;
use app\models\Buku;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Yii;

/**
 * TransaksiController handles transaction-related actions.
 */
class TransaksiController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'verifikasi-pinjaman' => ['POST'],
                    'proses-pengembalian' => ['POST'],
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Verifikasi peminjaman berdasarkan ID.
     * Method ini akan memverifikasi peminjaman dan menetapkan tanggal pinjam jika belum ada.
     *
     * @param int $id ID peminjaman yang akan diverifikasi
     * @return Response
     * @throws NotFoundHttpException jika peminjaman tidak ditemukan
     */
    public function actionVerifikasiPinjaman($id)
    {
        try {
            $model = $this->findModel($id);
            
            // Debug: Log informasi peminjaman (hanya aktif di DEV)
            if (YII_DEBUG) {
                \Yii::info("Verifikasi peminjaman ID: $id, Status saat ini: " . ($model->status ?? 'N/A'), 'transaksi');
            }

            // Cek apakah status sudah 'dipinjam' atau 'dikembalikan' (sudah diverifikasi)
            if ($model->status === 'dipinjam' || $model->status === 'dikembalikan') {
                Yii::$app->session->setFlash('info', 'Peminjaman sudah pernah diverifikasi sebelumnya.');
                return $this->redirect(['peminjaman/index']);
            }

            // Pastikan status adalah 'menunggu_verifikasi_admin'
            if ($model->status !== 'menunggu_verifikasi_admin') {
                Yii::$app->session->setFlash('error', 'Status peminjaman tidak valid untuk verifikasi. Status saat ini: ' . ($model->status ?? 'N/A'));
                return $this->redirect(['peminjaman/index']);
            }

            $transaction = Yii::$app->db->beginTransaction();
            try {
                // 1. Set tanggal pinjam (tglPinjam sesuai database)
                $tanggalPinjam = date('Y-m-d');
                if ($model->hasAttribute('tglPinjam')) {
                    $model->tglPinjam = $tanggalPinjam;
                } elseif ($model->hasAttribute('tanggalPinjam')) {
                    $model->tanggalPinjam = $tanggalPinjam;
                } elseif ($model->hasAttribute('tanggal_pinjam')) {
                    $model->tanggal_pinjam = $tanggalPinjam;
                }

                // 2. Set tglJatuhTempo (14 hari dari tanggal pinjam)
                if ($model->hasAttribute('tglJatuhTempo')) {
                    $model->tglJatuhTempo = date('Y-m-d', strtotime('+14 days'));
                }

                // 3. Ubah status menjadi 'dipinjam'
                $model->status = 'dipinjam';

                // 4. Simpan perubahan
                if (!$model->save(false)) {
                    $errors = $model->getFirstErrors();
                    throw new \Exception('Gagal menyimpan data verifikasi peminjaman: ' . implode(', ', $errors));
                }

                $transaction->commit();
                if (YII_DEBUG) {
                    \Yii::info("Peminjaman ID: $id berhasil diverifikasi", 'transaksi');
                }
                Yii::$app->session->setFlash('success', 'Peminjaman berhasil diverifikasi. Status telah diubah menjadi "Dipinjam".');

            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::error("Gagal memverifikasi peminjaman ID: $id - " . $e->getMessage(), 'transaksi');
                Yii::$app->session->setFlash('error', 'Gagal memverifikasi peminjaman: ' . $e->getMessage());
            }

        } catch (NotFoundHttpException $e) {
            Yii::$app->session->setFlash('error', 'Peminjaman tidak ditemukan.');
        } catch (\Exception $e) {
            \Yii::error("Error dalam actionVerifikasiPinjaman: " . $e->getMessage(), 'transaksi');
            Yii::$app->session->setFlash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        return $this->redirect(['peminjaman/index']);
    }

    /**
     * Proses pengembalian buku.
     * Method ini akan:
     * 1. Mengisi tglKembali dengan tanggal hari ini
     * 2. Memanggil fungsi hitungDenda() dari Model Peminjaman
     * 3. Menambah stok buku yang bersangkutan di tabel Buku
     * 4. Mengubah status menjadi 'dikembalikan'
     *
     * @param int $id ID peminjaman yang akan diproses
     * @return Response
     * @throws NotFoundHttpException jika peminjaman tidak ditemukan
     */
    public function actionProsesPengembalian($id)
    {
        $peminjaman = $this->findModel($id);

        // Cek apakah sudah dikembalikan
        if ($peminjaman->status === 'dikembalikan') {
            Yii::$app->session->setFlash('info', 'Buku sudah dikembalikan sebelumnya.');
            return $this->redirect(['peminjaman/index']);
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            // 1. Mengisi tglKembali dengan tanggal hari ini
            $tanggalKembali = date('Y-m-d H:i:s');
            // Cek kolom yang ada di database
            if ($peminjaman->hasAttribute('tanggalKembali')) {
                $peminjaman->tanggalKembali = $tanggalKembali;
            } elseif ($peminjaman->hasAttribute('tglKembali')) {
                $peminjaman->tglKembali = $tanggalKembali;
            } elseif ($peminjaman->hasAttribute('tanggal_kembali')) {
                $peminjaman->tanggal_kembali = $tanggalKembali;
            }

            // 2. Memanggil fungsi hitungDenda()
            $dendaId = $peminjaman->hitungDenda($tanggalKembali, 500);
            if ($dendaId !== null) {
                Yii::$app->session->setFlash('warning', 'Peminjaman terlambat. Denda telah dihitung.');
            }

            // 3. Menambah stok buku yang bersangkutan
            $peminjamanId = $peminjaman->getId();
            
            // Cek apakah tabel item_peminjaman ada
            try {
                $tableSchema = \Yii::$app->db->getTableSchema('item_peminjaman');
                if ($tableSchema) {
                    $itemPeminjamans = ItemPeminjaman::find()
                        ->where(['peminjamanID' => $peminjamanId])
                        ->all();

                    foreach ($itemPeminjamans as $item) {
                        // Mencari buku berdasarkan bukuID dari item peminjaman
                        $buku = Buku::findOne($item->bukuID);
                        if ($buku) {
                            // Pastikan stok tidak null
                            $stokSaatIni = $buku->stok ?? 0;
                            $buku->stok = $stokSaatIni + 1;
                            
                            if (!$buku->save(false)) {
                                throw new \Exception('Gagal menambah stok buku: ' . ($buku->judul ?? 'Unknown'));
                            }
                        }
                    }
                } else {
                    // Jika tabel tidak ada, skip penambahan stok (atau bisa ditambahkan logika alternatif)
                    Yii::$app->session->setFlash('warning', 'Tabel item_peminjaman tidak ditemukan. Stok buku tidak ditambahkan otomatis.');
                }
            } catch (\Exception $e) {
                // Jika error, skip penambahan stok
                Yii::$app->session->setFlash('warning', 'Gagal menambah stok buku: ' . $e->getMessage());
            }

            // 4. Mengubah status menjadi 'dikembalikan'
            $peminjaman->status = 'dikembalikan';

            if (!$peminjaman->save(false)) {
                throw new \Exception('Gagal menyimpan data pengembalian.');
            }

            $transaction->commit();
            Yii::$app->session->setFlash('success', 'Pengembalian buku berhasil diproses.');

        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Gagal memproses pengembalian: ' . $e->getMessage());
        }

        return $this->redirect(['peminjaman/index']);
    }

    /**
     * Menemukan model Peminjaman berdasarkan ID.
     *
     * @param int $id
     * @return Peminjaman
     * @throws NotFoundHttpException jika model tidak ditemukan
     */
    protected function findModel($id): Peminjaman
    {
        // Gunakan primary key dinamis untuk mencari model
        $primaryKey = Peminjaman::primaryKey();
        if (!empty($primaryKey)) {
            $key = $primaryKey[0];
            $model = Peminjaman::findOne([$key => $id]);
            if ($model !== null) {
                return $model;
            }
        } else {
            // Fallback: coba dengan ID langsung
            $model = Peminjaman::findOne($id);
            if ($model !== null) {
                return $model;
            }
        }

        throw new NotFoundHttpException('Peminjaman yang diminta tidak ditemukan.');
    }
}

