<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Categories;

/**
 * CategoriesSearch represents the model behind the search form about `app\models\Categories`.
 */
class CategoriesSearch extends Categories
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'category_id', 'service_level_agreement_asignment', 'service_level_agreement_completion'], 'integer'],
            [['name','description','id_area'], 'safe'],
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
        $query = Categories::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
       //$query->joinWith('idArea');

        $query->andFilterWhere([
            'id' => $this->id,
            'category_id' => $this->category_id,
            // 'id_area' => $this->id_area,
            'service_level_agreement_asignment' => $this->service_level_agreement_asignment,
            'service_level_agreement_completion' => $this->service_level_agreement_completion,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description]);
            // ->andFilterWhere(['like', 'areas.name', $this->id_area]);

        return $dataProvider;
    }
}
