<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class BukuSearch extends Buku
{
    public $bukuID;
    public $kategoriID;
    public $rakID;
    public $judul;

    public function rules(): array
    {
        return [
            [['bukuID', 'kategoriID', 'rakID'], 'integer'],
            [['judul'], 'safe'],
        ];
    }

    public function scenarios(): array
    {
        return Model::scenarios();
    }

    public function search(array $params): ActiveDataProvider
    {
        // Order by latest bukuID by default so admin list matches public catalog
        $query = Buku::find()->orderBy(['bukuID' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Apply filters for bukuID and rakID normally
        $query->andFilterWhere([
            'bukuID' => $this->bukuID,
            'rakID' => $this->rakID,
        ]);

        // Only filter by kategoriID if a non-empty value is provided
        if ($this->kategoriID !== null && $this->kategoriID !== '') {
            $query->andWhere(['kategoriID' => $this->kategoriID]);
        }

        $query->andFilterWhere(['like', 'judul', $this->judul]);

        return $dataProvider;
    }
}

