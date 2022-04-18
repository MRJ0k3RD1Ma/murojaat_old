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
    <script>
        var tashkilotadd = function(){}
    </script>
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

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="card-title">
                        Топшириқ маълумотлари
                    </h3>
                    <?= DetailView::widget([
                        'model' =>  $bajaruvchi,
                        'attributes' => [
                            'deadtime',
//                            'register_id',
                            [
                                'attribute'=>'register_id',
                                'value'=>function($d){
                                    return $d->register->number.'<br>'.$d->register->date;
                                },
                                'format'=>'raw'
                            ],
                            'task',
//                            'letter',
                            [
                                'attribute'=>'letter',
                                'value'=>function($d){
                                    if($d->letter){
                                        return "<a href='/upload/{$d->letter}'>Иловани юклаб олиш</a>";
                                    }
                                    return null;
                                }
                            ],
//                            'status',
                            [
                                'attribute'=>'status',
                                'value'=>function($d){
                                    return $d->status0->name;
                                }
                            ],
                            'created',
//                            'company_id',
                            [
                                'attribute'=>'company_id',
                                'value'=>function($d){
                                    return $d->company->name;
                                }
                            ],
                        ],
                    ]) ?>

                </div>
                <?php if($answer){?>
                    <div class="col-md-6">
                        <h3 class="card-title">
                            Мурожаатнинг жавоби
                        </h3>
                        <?= DetailView::widget([
                            'model' => $answer,
                            'attributes' => [
                                'number',
                                'date',
                                'preview',
                                'detail',
                                'name',
                                [
                                    'label'=>'Назорат',
                                    'value'=>function($d){
                                        return $d->register->control->name;
                                    }
                                ],
//                                'file',
                                [
                                    'attribute'=>'file',
                                    'value'=>function($d){
                                        if($d->file){
                                            return "<a href='/upload/{$d->file}'>Жавоб хатини юклаб олиш</a>";
                                        }else{
                                            return null;
                                        }
                                    },
                                    'format'=>'raw'
                                ],
//                                'reaply_send',
                                [
                                    'attribute'=>'reaply_send',
                                    'value'=>function($d){
                                        if($d->reaply_send == 0){
                                            return "Мурожаатчига жавоб хати юборилган";
                                        }else{
                                            return "Мурожаатчига жавоб хати юборилмаган";
                                        }
                                    }
                                ],
//                                'status'
                                [
                                    'attribute'=>'status',
                                    'value'=>function($d){
                                        return $d->status0->name;
                                    }
                                ],
                            ],
                        ]) ?>

                    </div>
                <?php }?>
            </div>

            <hr>
            <?php if($answer->status == 3 and $answer->register->company_id != Yii::$app->user->identity->company_id){?>
            <div id="accordion">
                <a href="#success" class="btn btn-primary"  data-toggle="collapse">Умумий жавоб сифатида қабул қилиш</a>
                <a href="<?= Yii::$app->urlManager->createUrl(['/appeal/acceptanswer','id'=>$answer->id])?>"
                   data-method="post" data-confirm="Сиз ростдан ҳам ушбу жавобни қабул қилмоқчимисиз?"
                   class="btn btn-success">Қабул қилиш</a>
                <a href="#deni" class="btn btn-danger" data-toggle="collapse">Рад этиш</a>

                <div id="success" class="collapse" style="margin-top: 20px; padding: 20px;border: 1px solid #28a745;" data-parent="#accordion">
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="card-title">
                                Қуйи ташкилот юборган жавобни умумий жавоб сифатида қабул қилиш
                            </h3>
                        </div>
                    </div>
                    <?php if($register->status != 4){?>
                        <?php if($register->parent_bajaruvchi_id){ echo $this->render('_answerform',['model'=>$answer]);}else{echo  $this->render('_closeform',['model'=>$model,'register'=>$register,'answer'=>$answer]);} ?>
                    <?php }else{echo "Мурожаатга жавоб юборилган";}?>

                </div>

                <div id="deni" class="collapse" style="margin-top: 20px; padding: 20px;border: 1px solid #dc3545;" data-parent="#accordion">

                    <?php
                    $com = new \app\models\AppealComment();
                    $com->answer_id = $answer->id;
                    $com->status = 6;
                    $form = ActiveForm::begin();?>
                        <?= $form->field($com,'comment')->textInput()->textInput()->label('Изоҳ')?>
                        <button class="btn btn-success" type="submit">
                            Жавобни рад қилиш
                        </button>
                    <?php ActiveForm::end()?>

                </div>

            </div>
            <?php }?>

        </div>


    </div>
