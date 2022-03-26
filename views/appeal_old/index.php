<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\AppealRegisterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Мурожаатлар рўйхати';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="appeal-register-index">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">
                        <?= Html::a('Рўйхатга олинмаган мурожаатлар', ['notregister'], ['class' => 'btn btn-primary']) ?>
                        <?= Html::a('Yangi murojaat qo\'shish', ['create'], ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
                <div class="card-body">

                    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                    <div class="table-responsive">

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
//                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
//                            'number',
//                            'date',
                            [
                                'label'=>'Рақаси ва санаси',
                                'value'=>function($d){
                                    $url = Yii::$app->urlManager->createUrl(['/appeal/update','id'=>$d->id]);
                                    return "<a href='{$url}'><span class='fa fa-edit'></span> № {$d->number} <br> {$d->date}</a>";
                                },
                                'format'=>'raw'
                            ],
//                            'appeal_id',
                            [
                                'attribute'=>'appeal_id',
                                'value'=>function($d){
                                    $q = $d->appeal->question;
                                    $url = Yii::$app->urlManager->createUrl(['/appeal/view','id'=>$d->id]);

                                    $res = $d->appeal->person_name.'<br>'.$q->group->code.'-'.$q->code.'.'.$q->name;
                                    return "<a href='{$url}'>{$res}</a>";
                                },
                                'format'=>'raw',
                            ],
//                            'rahbar_id',
                            [
                                'attribute'=>'rahbar_id',
                                'value'=>function($d){
                                    return isset($d->rahbar) ? $d->rahbar->name : '';
                                }
                            ],
                            [
                                'attribute'=>'ijrochi_id',
                                'value'=>function($d){
                                    return isset($d->ijrochi) ? $d->ijrochi->name : '';
                                }
                            ],
                            //'ijrochi_id',
                            //'users:ntext',
                            //'parent_bajaruvchi_id',
                            //'deadline',
                            [
                                'attribute'=>'control_id',
                                'value'=>function($d){
                                    return $d->control->name;
                                }
                            ],
//                            'deadtime',
                            [
                                'attribute'=>'deadtime',
                                'value'=>function($d){

                                    $datetime2 = date_create($d->deadtime);
                                    $datetime1 = date_create(date('Y-m-d'));

                                    $interval = date_diff($datetime1, $datetime2);
                                    $days = $interval->format('%a ');
                                    $ds = $interval->format('%R%a ');
                                    $class = "";
                                    if($ds < 0){
										$class = "bg-danger";
										$res = "<span class='{$class}' style='width: 100%; height: 100%; display: block;'>Муддати ўтган</span>";
									}elseif($ds <= 5){
										$class = "bg-warning";
										$res = "<span class='{$class}' style='width: 100%; height: 100%; display: block;'>".$days.' кун қолди'."</span>";
									}else{$res = "<span class='{$class}' style='width: 100%; height: 100%; display: block;'>".$days.' кун қолди'."</span>";}
									if(\app\models\AppealAnswer::find()->where(['appeal_id'=>$d->appeal_id])->andWhere(['status'=>0])->count('id') > 0){
										$info = "<span class='fa fa-info-circle bg-warning' style='width: 100%; display: block; text-align: center; padding:3px; font-size: 12px;'> Янги жавоб</span>";
										$res = $info . $res;
									}
                                    return $res;
                                },
                                'format'=>'raw'
                            ],
                            //'donetime',
                            //'control_id',

//                            'status',
                            [
                                'attribute'=>'status',
                                'value'=>function($d){
                                    if($d->status == 2){
                                        return "<span class='bg-success' style='width: 100%; height: 100%; display: block;text-align: center'>Бажарилган</span>";
                                    }else{
                                        return "<span class='bg-warning' style='width: 100%; height: 100%; display: block;text-align: center'>Жараёнда</span>";
                                    }
                                },'format'=>'raw'
                            ],
                            [
                                'label'=>'Жавоб бериган',
                                'value'=>function($d){
                                    if($d->appeal->answer_reply_send==1){
                                        return "<span class='bg-success' style='width: 100%; height: 100%; display: block;text-align: center'>Жавоб юборилган</span>";
                                    }else{
                                        return "<span class='bg-warning' style='width: 100%; height: 100%; display: block;text-align: center'>Жавоб юборилмаган</span>";
                                    }
                                },'format'=>'raw'

                            ],
                            //'created',
                            //'updated',
//                            'preview',
                            //'detail:ntext',
                            //'file',
                            //'company_id',
                            //'answer_send',
                            //'nazorat',
                            //'takroriy',
                            //'takroriy_id',
                            //'takroriy_date',
                            //'takroriy_number',
                        ],
                    ]); ?>

                    </div>

                </div>
            </div>
        </div>
    </div>



</div>
