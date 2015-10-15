<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AreaPersonal;

/**
 * AreaPersonalSearch represents the model behind the search form about `app\models\AreaPersonal`.
 */
class AreaPersonalSearch extends AreaPersonal
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['area_id', 'user_id', 'permission'], 'integer'],
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
        $query = AreaPersonal::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'area_id' => $this->area_id,
            'user_id' => $this->user_id,
            'permission' => $this->permission,
        ]);

        return $dataProvider;
    }
}
