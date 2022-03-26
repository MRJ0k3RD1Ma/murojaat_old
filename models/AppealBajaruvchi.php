<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "appeal_bajaruvchi".
 *
 * @property int $id
 * @property int $company_id
 * @property int $appeal_id
 * @property int $register_id
 * @property int $deadline
 * @property int $status
 * @property string $deadtime
 * @property string $created
 * @property string $letter
 */
class AppealBajaruvchi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'appeal_bajaruvchi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'appeal_id',  'deadtime'], 'required'],
            [['company_id', 'appeal_id', 'register_id', 'deadline','status'], 'integer'],
            [['deadtime','created'], 'safe'],
            ['letter','string'],
            ['letter','required','on'=>'send'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_id' => 'Ташкилот',
            'appeal_id' => 'Мурожаат',
            'register_id' => 'Регистратция',
            'deadline' => 'Муддат(кун)',
            'deadtime' => 'Муддат(Сана)',
            'status' => 'Ҳолат',
            'letter' => 'Кузатувчи хат',
            'created' => 'Юборилган вақти',
        ];
    }

    public function getCompany(){
        return $this->hasOne(Company::className(),['id'=>'company_id']);
    }
    public function getAppeal(){
        return $this->hasOne(Appeal::className(),['id'=>'appeal_id']);
    }
    public function getRegister(){
        return $this->hasOne(AppealRegister::className(),['id'=>'register_id']);
    }
    public function upload(){
        if($this->letter = UploadedFile::getInstance($this,'letter')){
            $name = microtime(true).'.'.$this->letter->extension;
            $this->letter->saveAs(Yii::$app->basePath.'/web/upload/'.$name);
            $this->letter = $name;
        }
    }
}
