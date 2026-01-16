<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class KategoriSearch extends Kategori
{
    public $kategoriID;
    
    public function rules(): array
    {
        return [
            [['kategoriID'], 'integer'],
            [['nama'], 'safe'],
        ];
    }

    public function scenarios(): array
    {
        return Model::scenarios();
    }

    public function search(array $params): ActiveDataProvider
    {
        $query = Kategori::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'kategoriID' => $this->kategoriID,
        ]);

        $query->andFilterWhere(['like', 'nama', $this->nama]);

        return $dataProvider;
    }
}

