<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Areas;

/**
 * AreasSearch represents the model behind the search form about `app\models\Areas`.
 */
class AreasSearch extends Areas
{
    public $responsable_name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'area_id', 'id_responsable'], 'integer'],
            [['name', 'description','responsable_name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Areas::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith('idResponsable');

        $dataProvider->sort->attributes['responsable_name'] = [
            'asc' => ['users.first_name' => SORT_ASC],
            'desc' => ['users.first_name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        $query->andFilterWhere([
            'id' => $this->id,
            'area_id' => $this->area_id,
            'id_responsable' => $this->id_responsable,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'users.first_name', $this->responsable_name]);

        return $dataProvider;
    }
}
