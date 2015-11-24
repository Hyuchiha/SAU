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
    public $area_Name;
    public $user_Name;
    public $user_lastname;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['area_id', 'user_id', 'permission'], 'integer'],
            [['area_Name', 'user_Name','user_lastname'], 'safe']
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

        $query->joinWith('area');
        $query->joinWith('user');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['area_Name'] = [
            'asc' => ['areas.name' => SORT_ASC],
            'desc' => ['areas.name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['user_Name'] = [
            'asc' => ['users.first_name' => SORT_ASC],
            'desc' => ['users.first_name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['user_lastname'] = [
            'asc' => ['users.lastname' => SORT_ASC],
            'desc' => ['users.lastname' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'name' => $this->area_id,
            'first_name' => $this->user_id,
            'permission' => $this->permission,
        ])->andFilterWhere(['like', 'areas.name', $this->area_Name])
        ->andFilterWhere(['like', 'user.first_name', $this->user_Name])
        ->andFilterWhere(['like', 'user.lastname', $this->user_lastname]);

        return $dataProvider;
    }
}
