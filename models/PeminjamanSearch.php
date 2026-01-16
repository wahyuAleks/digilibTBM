<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class PeminjamanSearch extends Peminjaman
{
    public $anggotaID;
    public $status;
    public $search; // Untuk pencarian nama anggota atau judul buku

    public function rules(): array
    {
        return [
            [['anggotaID'], 'integer'],
            [['status', 'search'], 'safe'],
        ];
    }

    public function scenarios(): array
    {
        return Model::scenarios();
    }

    public function search(array $params): ActiveDataProvider
    {
        // Cek apakah tabel item_peminjaman ada sebelum eager loading
        $withRelations = ['anggota.user'];
        try {
            $tableSchema = \Yii::$app->db->getTableSchema('item_peminjaman');
            if ($tableSchema) {
                $withRelations[] = 'itemPeminjamans.buku';
            }
        } catch (\Exception $e) {
            // Tabel tidak ada, skip eager loading itemPeminjamans
        }
        
        // Gunakan try-catch untuk menangkap error saat membuat query
        try {
            $query = Peminjaman::find()->with($withRelations);
        } catch (\Exception $e) {
            // Jika error (misalnya tabel tidak ada), buat query tanpa eager loading itemPeminjamans
            $query = Peminjaman::find()->with(['anggota.user']);
        }

        // Set default order - coba kolom yang umum ada
        try {
            $tableSchema = \Yii::$app->db->getTableSchema('peminjaman');
            if ($tableSchema) {
                $columns = array_keys($tableSchema->columns);
                // Coba cari kolom id atau primary key
                $primaryKey = Peminjaman::primaryKey();
                if (!empty($primaryKey) && in_array($primaryKey[0], $columns)) {
                    $query->orderBy([$primaryKey[0] => SORT_DESC]);
                } elseif (in_array('id', $columns)) {
                    $query->orderBy(['id' => SORT_DESC]);
                } elseif (in_array('peminjamanID', $columns)) {
                    $query->orderBy(['peminjamanID' => SORT_DESC]);
                } elseif (in_array('anggotaID', $columns)) {
                    $query->orderBy(['anggotaID' => SORT_DESC]);
                }
            }
        } catch (\Exception $e) {
            // Jika error, gunakan orderBy anggotaID sebagai fallback
            $query->orderBy(['anggotaID' => SORT_DESC]);
        }
        
        // Tentukan sort attributes berdasarkan kolom yang ada - HANYA kolom yang benar-benar ada
        $sortAttributes = [];
        $defaultOrderKey = null;
        try {
            $tableSchema = \Yii::$app->db->getTableSchema('peminjaman');
            if ($tableSchema) {
                $columns = array_keys($tableSchema->columns);
                $primaryKey = Peminjaman::primaryKey();
                $key = !empty($primaryKey) ? $primaryKey[0] : 'id';
                
                // Pastikan primary key ada di kolom - JANGAN gunakan peminjamanID jika tidak ada
                if (!in_array($key, $columns)) {
                    if (in_array('id', $columns)) {
                        $key = 'id';
                    } elseif (in_array('anggotaID', $columns)) {
                        $key = 'anggotaID';
                    } else {
                        // Jangan gunakan peminjamanID, gunakan kolom pertama yang ada
                        $key = !empty($columns) ? $columns[0] : 'anggotaID';
                    }
                }
                
                // Hanya tambahkan attribute yang benar-benar ada di tabel
                if (in_array($key, $columns)) {
                    $sortAttributes[$key] = [
                        'asc' => [$key => SORT_ASC],
                        'desc' => [$key => SORT_DESC],
                    ];
                    $defaultOrderKey = $key;
                }
                // Hanya tambahkan anggotaID jika ada dan berbeda dengan key
                if (in_array('anggotaID', $columns) && $key !== 'anggotaID') {
                    $sortAttributes['anggotaID'] = [
                        'asc' => ['anggotaID' => SORT_ASC],
                        'desc' => ['anggotaID' => SORT_DESC],
                    ];
                }
            }
        } catch (\Exception $e) {
            // Jika error, gunakan default tanpa sort
        }
        
        // Buat data provider tanpa sort jika tidak ada attributes yang valid
        $dataProviderConfig = [
            'query' => $query,
        ];
        
        // Hanya tambahkan sort jika ada attributes yang valid
        if (!empty($sortAttributes) && $defaultOrderKey) {
            $dataProviderConfig['sort'] = [
                'attributes' => $sortAttributes,
                'defaultOrder' => [$defaultOrderKey => SORT_DESC],
            ];
        }
        
        $dataProvider = new ActiveDataProvider($dataProviderConfig);

        // Simpan status yang sudah diset sebelum load() menimpa
        $statusBeforeLoad = $this->status;
        
        $this->load($params);
        
        // PENTING: Jika status sudah diset sebelum load() (dari controller),
        // pastikan status tidak ditimpa oleh load() jika params tidak mengubahnya
        // Atau jika params mengubah status menjadi kosong, kembalikan ke nilai sebelumnya
        if (!empty($statusBeforeLoad)) {
            // Jika setelah load() status menjadi kosong atau berbeda, gunakan yang sudah diset
            if (empty($this->status) || ($this->status !== $statusBeforeLoad && !isset($params['PeminjamanSearch']['status']))) {
                $this->status = $statusBeforeLoad;
            }
            // Jika params['PeminjamanSearch']['status'] ada, gunakan yang dari params
            elseif (isset($params['PeminjamanSearch']['status']) && !empty($params['PeminjamanSearch']['status'])) {
                $this->status = $params['PeminjamanSearch']['status'];
            }
        }

        // Validasi kolom sebelum menggunakan filter
        $validColumns = [];
        try {
            $tableSchema = \Yii::$app->db->getTableSchema('peminjaman');
            if ($tableSchema) {
                $validColumns = array_keys($tableSchema->columns);
            }
        } catch (\Exception $e) {
            // Jika error, gunakan kolom default
            $validColumns = ['anggotaID', 'status'];
        }

        // PENTING: Filter status HARUS diterapkan SEBELUM validasi
        // karena validasi mungkin gagal tapi filter tetap harus diterapkan
        // Juga pastikan status tidak kosong dan kolom status ada
        if (!empty($this->status) && in_array('status', $validColumns)) {
            // Gunakan trim untuk menghindari masalah spasi
            $statusValue = trim($this->status);
            
            // Debug: Log status yang akan difilter
            // \Yii::info("Filtering dengan status: '$statusValue'", 'peminjaman');
            
            if ($statusValue === 'dipinjam') {
                // Tab aktif: tampilkan dipinjam dan menunggu verifikasi admin
                $query->andWhere(['in', 'status', ['dipinjam', 'menunggu_verifikasi_admin']]);
            } elseif ($statusValue === 'menunggu_verifikasi_admin') {
                // Tab menunggu: hanya tampilkan yang menunggu verifikasi
                // Gunakan exact match untuk memastikan filter benar
                $query->andWhere(['status' => 'menunggu_verifikasi_admin']);
            } else {
                // Status lainnya (dikembalikan, dll)
                $query->andWhere(['status' => $statusValue]);
            }
        }

        // Hanya filter jika kolom ada
        if (!empty($this->anggotaID) && in_array('anggotaID', $validColumns)) {
            $query->andFilterWhere([
                'anggotaID' => $this->anggotaID,
            ]);
        }

        // Validasi dilakukan setelah filter diterapkan
        // Tapi jangan return jika validasi gagal, karena filter sudah diterapkan
        if (!$this->validate()) {
            // Tetap return dataProvider meskipun validasi gagal
            // karena filter status sudah diterapkan
            return $dataProvider;
        }

        // Filter berdasarkan nama anggota atau judul buku
        if (!empty($this->search)) {
            // Dapatkan primary key dan validasi kolom yang ada
            $primaryKey = Peminjaman::primaryKey();
            $key = !empty($primaryKey) ? $primaryKey[0] : 'id';
            
            // Validasi bahwa kolom primary key ada di tabel
            try {
                $peminjamanTableSchema = \Yii::$app->db->getTableSchema('peminjaman');
                if ($peminjamanTableSchema) {
                    $peminjamanColumns = array_keys($peminjamanTableSchema->columns);
                    // Jika primary key tidak ada di kolom, cari alternatif
                    if (!in_array($key, $peminjamanColumns)) {
                        // Coba cari kolom id yang umum
                        if (in_array('id', $peminjamanColumns)) {
                            $key = 'id';
                        } elseif (in_array('peminjamanID', $peminjamanColumns)) {
                            $key = 'peminjamanID';
                        } elseif (in_array('anggotaID', $peminjamanColumns)) {
                            $key = 'anggotaID';
                        } else {
                            // Jika tidak ada kolom yang cocok, gunakan kolom pertama
                            $key = !empty($peminjamanColumns) ? $peminjamanColumns[0] : 'id';
                        }
                    }
                }
            } catch (\Exception $e) {
                // Jika error, gunakan fallback
                $key = 'id';
            }
            
            // Cek apakah tabel item_peminjaman ada
            try {
                $itemPeminjamanTableSchema = \Yii::$app->db->getTableSchema('item_peminjaman');
                if ($itemPeminjamanTableSchema) {
                    $itemPeminjamanColumns = array_keys($itemPeminjamanTableSchema->columns);
                    // Cek kolom peminjamanID di tabel item_peminjaman
                    $itemPeminjamanKey = 'peminjamanID';
                    if (!in_array($itemPeminjamanKey, $itemPeminjamanColumns)) {
                        // Coba alternatif
                        if (in_array('peminjaman_id', $itemPeminjamanColumns)) {
                            $itemPeminjamanKey = 'peminjaman_id';
                        } else {
                            // Jika tidak ada, skip join dengan item_peminjaman
                            $itemPeminjamanTableSchema = null;
                        }
                    }
                    
                    if ($itemPeminjamanTableSchema) {
                        // Pastikan kolom untuk groupBy ada di tabel peminjaman
                        $peminjamanTableSchema = \Yii::$app->db->getTableSchema('peminjaman');
                        if ($peminjamanTableSchema && in_array($key, array_keys($peminjamanTableSchema->columns))) {
                            $query->joinWith(['anggota.user'])
                                  ->leftJoin('item_peminjaman', "item_peminjaman.{$itemPeminjamanKey} = peminjaman.{$key}")
                                  ->leftJoin('buku', 'buku.bukuID = item_peminjaman.bukuID')
                                  ->andFilterWhere([
                                      'or',
                                      ['like', 'user.nama', $this->search],
                                      ['like', 'user.email', $this->search],
                                      ['like', 'buku.judul', $this->search],
                                  ])
                                  ->groupBy([new \yii\db\Expression("peminjaman.{$key}")]); // Group by untuk menghindari duplikasi
                        } else {
                            // Jika kolom tidak ada, gunakan tanpa groupBy
                            $query->joinWith(['anggota.user'])
                                  ->leftJoin('item_peminjaman', "item_peminjaman.{$itemPeminjamanKey} = peminjaman.{$key}")
                                  ->leftJoin('buku', 'buku.bukuID = item_peminjaman.bukuID')
                                  ->andFilterWhere([
                                      'or',
                                      ['like', 'user.nama', $this->search],
                                      ['like', 'user.email', $this->search],
                                      ['like', 'buku.judul', $this->search],
                                  ]);
                        }
                    } else {
                        // Jika tabel tidak ada atau kolom tidak cocok, hanya filter berdasarkan nama anggota
                        $query->joinWith(['anggota.user'])
                              ->andFilterWhere([
                                  'or',
                                  ['like', 'user.nama', $this->search],
                                  ['like', 'user.email', $this->search],
                              ]);
                    }
                } else {
                    // Jika tabel tidak ada, hanya filter berdasarkan nama anggota
                    $query->joinWith(['anggota.user'])
                          ->andFilterWhere([
                              'or',
                              ['like', 'user.nama', $this->search],
                              ['like', 'user.email', $this->search],
                          ]);
                }
            } catch (\Exception $e) {
                // Jika error, hanya filter berdasarkan nama anggota
                $query->joinWith(['anggota.user'])
                      ->andFilterWhere([
                          'or',
                          ['like', 'user.nama', $this->search],
                          ['like', 'user.email', $this->search],
                      ]);
            }
        }

        return $dataProvider;
    }
}

