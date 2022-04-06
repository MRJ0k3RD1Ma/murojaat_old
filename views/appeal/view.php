<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Appeal */
/* @var $register app\models\AppealRegister */
/* @var $answer app\models\AppealAnswer */
/* @var $ans app\models\AppealAnswer */

$this->title = $model->person_name;
$this->params['breadcrumbs'][] = ['label' => 'Мурожаатлар', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    Муожаат маълумотлари
                </h3>
            </div>
            <div class="card-body">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'id',
                        'appeal_detail',
//                        'appeal_file',
                        [
                            'attribute'=>'appeal_file',
                            'value'=>function($d){
                                if($d->appeal_file){
                                    return "<a href='/upload/{$d->appeal_file}'>Иловани юклаб олиш</a>";
                                }else{
                                    return null;
                                }
                            },
                            'format'=>'raw'
                        ],
//                        'deadtime',

                        'updated',
//                        'boshqa_tashkilot',
                        [
                            'attribute'=>'question_id',
                            'value'=>function($d){
                                if($d->question_id){
                                    $q = $d->question;
                                    return $q->group->code.'-'.$q->code.'.'.$q->name;
                                }
                                return null;
                            },
                        ],
                        [
                            'attribute'=>'appeal_shakl_id',
                            'value'=>function($d){
                                return $d->appealShakl->name;
                            },
                        ],
                        [
                            'attribute'=>'appeal_type_id',
                            'value'=>function($d){
                                return $d->appealType->name;
                            },
                        ],
                        [
                            'label'=>'Қабул қилган ташкилот',
                            'attribute'=>'boshqa_tashkilot',
                            'value'=>function($d){
                                if($d->boshqa_tashkilot){
                                    return $d->boshqaTashkilot->name.'<br>'.$d->boshqa_tashkilot_number.' '.$d->boshqa_tashkilot_date;
                                }else{
                                    return "Бевосита";
                                }
                            }
                        ],
                        [
                            'attribute'=>'company_id',
                            'value'=>function($d){
                                return $d->company->name;
                            }
                        ],

                    ],
                ]) ?>


            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    Мурожаатчи ҳақида
                </h3>
            </div>
            <div class="card-body">

                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
//                        'id',
                        'person_name',
                        'person_phone',
                        'date_of_birth',
//                        'gender',
                        [
                            'attribute'=>'gender',
                            'value'=>function($d){
                                return Yii::$app->params['gender'][$d->gender];
                            }
                        ],
                        [
                            'attribute'=>'region_id',
                            'value'=>function($d){
                                return $d->region->name;
                            }
                        ],
                        [
                            'attribute'=>'district_id',
                            'value'=>function($d){
                                return $d->district->name;
                            }
                        ],
                        [
                            'attribute'=>'village_id',
                            'value'=>function($d){
                                return $d->village->name;
                            }
                        ],
                        'address',
                        'email',
                        'businessman',
                    ],
                ]) ?>


            </div>
        </div>
    </div>
</div>




        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    Ариза маълумотлари
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-<?php if($register->status == 0 or $register->status == 1 or $register->status == 2) echo 12; else echo 6?>">
                <?= DetailView::widget([
                    'model' => $register,
                    'attributes' => [
                        'number',
                        'date',
                        'deadtime',
                        'donetime',
                        'control_id',
                        'status',
                        'preview',
                        'detail',
                        'file',
                        'nazorat',
                        'takroriy',

                    ],
                ]) ?>

                    </div>
                </div>
                <hr>
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Active</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                    </li>
                </ul>


            </div>


</div>

