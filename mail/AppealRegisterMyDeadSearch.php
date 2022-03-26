<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AppealRegister;

/**
 * AppealRegisterSearch represents the model behind the search form of `app\models\AppealRegister`.
 */
class AppealRegisterMyDeadSearch extends AppealRegister
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'appeal_id', 'rahbar_id', 'ijrochi_id', 'parent_bajaruvchi_id', 'deadline', 'control_id', 'status', 'company_id', 'answer_send', 'nazorat', 'takroriy', 'takroriy_id'], 'integer'],
            [['number', 'date', 'users', 'deadtime', 'donetime', 'created', 'updated', 'preview', 'detail', 'file', 'takroriy_date', 'takroriy_number'], 'safe'],
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
        $sql = "(ijrochi_id={$user->id} or rahbar_id={$user->id} or (users like '%\"{$user->id}\"%' and not user_answer like '%\"{$user->id}\"%')) and deadtime<date(now())";
        $query = AppealRegister::find()->where(['company_id'=>$user->company_id])
        ->andWhere(['<>','status',2])->andWhere($sql)->orderBy(['deadtime'=>SORT_ASC]);


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
            'status' => $this->status,
            'created' => $this->created,
            'updated' => $this->updated,
            'company_id' => $this->company_id,
            'answer_send' => $this->answer_send,
            'nazorat' => $this->nazorat,
            'takroriy' => $this->takroriy,
            'takroriy_id' => $this->takroriy_id,
            'takroriy_date' => $this->takroriy_date,
        ]);

        $query->andFilterWhere(['like', 'number', $this->number])
            ->andFilterWhere(['like', 'users', $this->users])
            ->andFilterWhere(['like', 'preview', $this->preview])
            ->andFilterWhere(['like', 'detail', $this->detail])
            ->andFilterWhere(['like', 'file', $this->file])
            ->andFilterWhere(['like', 'takroriy_number', $this->takroriy_number]);

        return $dataProvider;
    }
}
