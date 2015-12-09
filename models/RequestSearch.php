<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Request;

/**
 * RequestSearch represents the model behind the search form about `app\models\Request`.
 */
class RequestSearch extends Request
{
    public $area_name;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['id', 'area_id','user_id'], 'integer'],
            [['id', 'area_id'], 'integer'],
            [['name', 'email', 'subject', 'description', 'creation_date', 'completion_date', 'status','area_name'], 'safe'],
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
        $query = Request::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith(['area','users']);

        $dataProvider->sort->attributes['area_name'] = [
            'asc' => ['areas.name' => SORT_ASC],
            'desc' => ['areas.name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'request.id' => $this->id,
            'request.creation_date' => $this->creation_date,
            'request.completion_date' => $this->completion_date,
        ]);
        
        if(!$this->status == "Rechazado" || !$this->status == "Finalizado"){
			$query->orFilterWhere(['like', 'request.status', "Nuevo"])
			->orFilterWhere(['like', 'request.status', "En proceso"])
			->orFilterWhere(['like', 'request.status', "Asignado"])
			->orFilterWhere(['like', 'request.status', "Verificado"]);
		}
        
        if(!Yii::$app->user->can('administrator') || !Yii::$app->user->can('executive')){
            //Quien creó la solicitud
            $query->andFilterWhere([
                'request.user_id' => $_SESSION['__id']
            ]);
            if(Yii::$app->user->can('responsibleArea')){
                //Quien tiene asignada la solicitud y pertenece a cierta área
                $query->orFilterWhere(['users_request.user_id' => $_SESSION['__id']])
                    ->orFilterWhere(['request.area_id' => $this->area_id,]);
            }else{
                if(Yii::$app->user->can('employeeArea')){
                    //Quien tiene asignada la solicitud
                    $query->orFilterWhere([
                        'users_request.user_id' => $_SESSION['__id'],
                    ]);
                }
            }
        }
        
        $query->andFilterWhere(['like', 'request.name', $this->name])
            ->andFilterWhere(['like', 'request.email', $this->email])
            ->andFilterWhere(['like', 'request.subject', $this->subject])
            ->andFilterWhere(['like', 'request.description', $this->description])
            ->andFilterWhere(['like', 'request.status', $this->status])
            ->andFilterWhere(['like', 'areas.name', $this->area_name])
			->andFilterWhere(['like', 'request.id', $this->id]);
        
        return $dataProvider;
    }
}
