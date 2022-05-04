<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "request".
 *
 * @property int $id
 * @property int $sender_id
 * @property int $reciever_id
 * @property int $type_id
 * @property int $register_id
 * @property int $appeal_id
 * @property int $status_id
 * @property string|null $detail
 * @property string|null $date
 * @property string|null $file
 * @property string $created
 * @property string $updated
 */
class Request extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'request';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sender_id', 'reciever_id', 'type_id', 'register_id', 'appeal_id'], 'required'],
            [['sender_id', 'reciever_id', 'type_id', 'register_id', 'appeal_id', 'status_id'], 'integer'],
            [['detail'], 'string'],
            [['date', 'created', 'updated'], 'safe'],
            [['file'], 'string', 'max' => 255],
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
            'type_id' => 'Type ID',
            'register_id' => 'Register ID',
            'appeal_id' => 'Appeal ID',
            'status_id' => 'Status ID',
            'detail' => 'Detail',
            'date' => 'Date',
            'file' => 'File',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}
