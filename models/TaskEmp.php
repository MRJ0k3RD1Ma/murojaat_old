<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "task_emp".
 *
 * @property int $id
 * @property int $sender_id
 * @property int $reciever_id
 * @property int $register_id
 * @property int $appeal_id
 * @property string $deadtime
 * @property string $letter
 * @property string $created
 * @property string $updated
 * @property int $status
 */
class TaskEmp extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task_emp';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sender_id', 'reciever_id', 'register_id', 'appeal_id', 'deadtime', 'letter'], 'required'],
            [['sender_id', 'reciever_id', 'register_id', 'appeal_id', 'status'], 'integer'],
            [['deadtime', 'created', 'updated'], 'safe'],
            [['letter'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sender_id' => 'Sender ID',
            'reciever_id' => 'Reciever ID',
            'register_id' => 'Register ID',
            'appeal_id' => 'Appeal ID',
            'deadtime' => 'Deadtime',
            'letter' => 'Letter',
            'created' => 'Created',
            'updated' => 'Updated',
            'status' => 'Status',
        ];
    }
}
