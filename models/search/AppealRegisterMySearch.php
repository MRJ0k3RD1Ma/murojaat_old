<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AppealRegister;

/**
 * AppealRegisterSearch represents the model behind the search form of `app\models\AppealRegister`.
 */
class AppealRegisterMySearch extends AppealRegister
{
    public $question_id, $person_name,$person_phone,$gender,$date_of_birth,$region_id,$district_id,$village_id,$address;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'appeal_id', 'rahbar_id','question_id','gender', 'ijrochi_id', 'parent_bajaruvchi_id', 'deadline', 'status', 'control_id','company_id', 'answer_send', 'nazorat', 'takroriy', 'takroriy_id'], 'integer'],
            [['number', 'date', 'users','date_of_birth', 'person_name','person_phone','deadtime', 'donetime', 'created', 'updated', 'preview', 'detail', 'file', 'takroriy_date', 'takroriy_number'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $user = \Yii::$app->user->identity;
        $query = AppealRegister::find()
            ->innerJoin('appeal','appeal.id=appeal_register.appeal_id')
            ->where(['appeal_register.company_id'=>$user->company_id]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if($this->status == -1){
            $query->where('appeal_register.id in (select register_id from task_emp where reciever_id='.\Yii::$app->user->id.' or sender_id='.\Yii::$app->user->id.')')
                ->orderBy(['appeal_register.deadtime'=>SORT_DESC]);
        }else{
            $query->where('appeal_register.id in (select register_id from task_emp where (reciever_id='.\Yii::$app->user->id.' or sender_id='.\Yii::$app->user->id.') and status='.$this->status.')')
                ->orderBy(['appeal_register.deadtime'=>SORT_DESC]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'date' => $this->date,
            'appeal_id' => $this->appeal_id,
            'rahbar_id' => $this->rahbar_id,
            'ijrochi_id' => $this->ijrochi_id,
            'parent_bajaruvchi_id' => $this->parent_bajaruvchi_id,
            'deadline' => $this->deadline,
            'deadtime' => $this->deadtime,
            'donetime' => $this->donetime,
            'control_id' => $this->control_id,
            'created' => $this->created,
            'updated' => $this->updated,
            'company_id' => $this->company_id,
            'answer_send' => $this->answer_send,
            'nazorat' => $this->nazorat,
            'takroriy' => $this->takroriy,
            'takroriy_id' => $this->takroriy_id,
            'takroriy_date' => $this->takroriy_date,
            'appeal.date_of_birth' => $this->date_of_birth,
            'appeal.gender' => $this->gender,
            'appeal.question_id' => $this->question_id,
            'appeal.region_id' => $this->region_id,
            'appeal.district_id' => $this->district_id,
            'appeal.village_id' => $this->village_id,
            'appeal.address' => $this->address,
        ]);

        $query->andFilterWhere(['like', 'number', $this->number])
            ->andFilterWhere(['like', 'users', $this->users])
            ->andFilterWhere(['like', 'preview', $this->preview])
            ->andFilterWhere(['like', 'detail', $this->detail])
            ->andFilterWhere(['like', 'file', $this->file])
            ->andFilterWhere(['like', 'takroriy_number', $this->takroriy_number])
            ->andFilterWhere(['like', 'appeal.person_phone', $this->person_phone])
            ->andFilterWhere(['like', 'appeal.person_name', $this->person_name]);

        return $dataProvider;
    }
}
