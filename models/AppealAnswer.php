<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "appeal_answer".
 *
 * @property int $id
 * @property int $appeal_id
 * @property int $register_id
 * @property string $preview
 * @property string $detail
 * @property string $number
 * @property string $date
 * @property string $tarqatma_number
 * @property string $tarqatma_date
 * @property int $bajaruvchi_id
 * @property int $reaply_send
 * @property int $status
 * @property int $status_boshqa
 * @property string $name
 * @property string $created
 * @property string $updated
 */
class AppealAnswer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'appeal_answer';
    }
    public $n_olish;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['appeal_id', 'register_id', 'detail',  'bajaruvchi_id',], 'required'],
            [['appeal_id', 'register_id', 'bajaruvchi_id', 'reaply_send','status','status_boshqa','n_olish'], 'integer'],
            [['detail'], 'string'],
            [['date', 'tarqatma_date', 'created', 'updated'], 'safe'],
            [['preview', 'number', 'tarqatma_number', 'name','file'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Ҳолат',
            'n_olish' => 'Назоратдан олиш',
            'appeal_id' => 'Ҳужжат рақами',
            'register_id' => 'регистрация',
            'preview' => 'Ҳужжат номи',
            'detail' => 'Мазмуни',
            'number' => 'Рақами',
            'date' => 'Санаси',
            'tarqatma_number' => 'Тарқатма рақами',
            'tarqatma_date' => 'Тарқатма санаси',
            'bajaruvchi_id' => 'Юборувчи',
            'reaply_send' => 'Жавоб мурожаатчига юборилди',
            'name' => 'Ижрочи',
            'file' => 'Файл',
            'created' => 'Юборилди',
            'updated' => 'Тасдиқланди',
            'status_boshqa' => 'Ҳолат',
        ];
    }
    public function getAppeal(){
        return $this->hasOne(Appeal::className(),['id'=>'appeal_id']);
    }
    public function getRegister(){
        return $this->hasOne(AppealRegister::className(),['id'=>'register_id']);
    }
    public function getBajaruvchi(){
        return $this->hasOne(User::className(),['id'=>'bajaruvchi_id']);
    }
}
