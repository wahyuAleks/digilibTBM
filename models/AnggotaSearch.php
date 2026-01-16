<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class AnggotaSearch extends Anggota
{
    public $anggotaID;
    public $userID;
    public $nama;

    public function rules(): array
    {
        return [
            [['anggotaID', 'userID'], 'integer'],
            [['nama'], 'safe'],
        ];
    }

    public function scenarios(): array
    {
        return Model::scenarios();
    }

    public function search(array $params): ActiveDataProvider
    {
        $query = Anggota::find()->with('user'); // Eager load relasi user

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'anggotaID' => $this->anggotaID,
        ]);

        // Filter berdasarkan userID melalui relasi (anggotaID = userid)
        if (!empty($this->userID)) {
            $query->andFilterWhere(['anggotaID' => $this->userID]);
        }

        // Filter berdasarkan nama user, bukan nama anggota (karena kolom nama tidak ada di tabel anggota)
        if (!empty($this->nama)) {
            $query->joinWith('user')
                  ->andFilterWhere(['like', 'user.nama', $this->nama]);
        }

        return $dataProvider;
    }
}

