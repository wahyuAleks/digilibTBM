<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class RakSearch extends Rak
{
    public function rules(): array
    {
        return [
            [['id'], 'integer'],
            [['nama', 'lokasi'], 'safe'],
        ];
    }

    public function scenarios(): array
    {
        return Model::scenarios();
    }

    public function search(array $params): ActiveDataProvider
    {
        $query = Rak::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'nama', $this->nama])
            ->andFilterWhere(['like', 'lokasi', $this->lokasi]);

        return $dataProvider;
    }
}

