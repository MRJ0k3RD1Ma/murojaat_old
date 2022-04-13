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
                <div class="col-md-<?php if($result->status == 0 or $result->status == 1 or $result->status == 2) echo 12; else echo 6?>">
                    <h3 class="card-title">
                        Ариза маълумотлари
                    </h3>
                    <?= DetailView::widget([
                        'model' => $register,
                        'attributes' => [
                            'number',
                            'date',
//                        'deadtime',
                            [
                                'attribute'=>'deadtime',
                                'value'=>function($d){
                                    return $d->deadline.' кун '.$d->deadtime;
                                }
                            ],
                            'donetime',
//                        'control_id',
                            [
                                'attribute'=>'control_id',
                                'value'=>function($d){
                                    return $d->control->name;
                                }
                            ],
//                        'status',
                            'preview',
//                        'detail',
//                        'file',
//                        'nazorat',
                            [
                                'attribute'=>'nazorat',
                                'value'=>function($d){
                                    return Yii::$app->params['nazorat'][$d->nazorat];
                                }
                            ],
//                        'takroriy',
//                        'rahbar_id',
//                        'ijrochi_id',
                            [
                                'attribute'=>'rahbar_id',
                                'value'=>function($d){
                                    return @$d->rahbar->name;
                                }
                            ],
                            [
                                'attribute'=>'ijrochi_id',
                                'value'=>function($d){
                                    return @$d->ijrochi->name;
                                }
                            ],
                        ],
                    ]) ?>

                </div>
                <?php if($result->status > 2 and $result->parent_bajaruvchi_id){?>
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
            <div id="accordion">
                <a href="#success" class="btn btn-primary"  data-toggle="collapse">Умумий жавоб сифатида қабул қилиш</a>
                <a href="<?= Yii::$app->urlManager->createUrl(['/appeal/acceptanswer','id'=>$answer->id])?>"
                   data-method="post" data-confirm="Сиз ростдан ҳам ушбу жавобни қабул қилмоқчимисиз?"
                   class="btn btn-success">Қабул қилиш</a>
                <a href="#deni" class="btn btn-danger" data-toggle="collapse">Рад этиш</a>

                <div id="success" class="collapse" style="margin-top: 20px; padding: 20px;border: 1px solid #28a745;" data-parent="#accordion">

                    <?php if($register->status != 3 and $register->status != 4){?>
                        <?php if($register->parent_bajaruvchi_id){ echo $this->render('_answerform',['model'=>$answer]);} ?>
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

        </div>


    </div>
