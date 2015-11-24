<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\UsersRequest;

/**
 * UsersRequestSearch represents the model behind the search form about `app\models\UsersRequest`.
 */
class UsersRequestSearch extends UsersRequest
{
    public $user_name;
    public $request_Subject;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['request_id', 'user_id'], 'integer'],
            [['user_name','request_Subject'], 'safe']
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
        $query = UsersRequest::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith(['request','user']);

        $dataProvider->sort->attributes['user_name'] = [
            'asc' => ['user.first_name' => SORT_ASC],
            'desc' => ['user.first_name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['request_Subject'] = [
            'asc' => ['request.subject' => SORT_ASC],
            'desc' => ['request_subject' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'request_id' => $this->request_id,
            'user_id' => $this->user_id,
        ])
        ->andFilterWhere(['like', 'user.first_name', $this->user_name])
        ->andFilterWhere(['like', 'resquest.subject', $this->request_Subject]);

        return $dataProvider;
    }
}
