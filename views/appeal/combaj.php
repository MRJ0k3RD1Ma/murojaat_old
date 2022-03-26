<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\AppealBajaruvchiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $company->name.'га юборилган мурожаатлар рўйхати';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="appeal-bajaruvchi-index">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">

                </div>
                <div class="card-body">

                    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
//                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            [
                                'label'=>'Ҳолати',
                                'value'=>function($d){
                                    if($d->status == 0){
                                        return "Рўйхатга олинмаган";
                                    }elseif($d->status == 1){
                                        return "Жараёнда";
                                    }else{
                                        return "Бажарилган";
                                    }
                                },
                                'format'=>'raw'
                            ],
                            [
                                'label'=>'Мурожаатчи',
                                'value'=>function($d){
                                    if($s = $d->appeal){
                                        $res = $s->person_name;
                                        if($s->question){
                                            $res .= '<br>'.$s->question->name;
                                        }
                                        return $res;
                                    }else{
                                        return null;
                                    }
                                },
                                'format'=>'raw'
                            ],
                            [
                                'attribute'=>'deadtime',
                                'value'=>function($d){

                                    if($d->status == 2){
                                        return "<span class='bg-success' style='width: 100%; height: 100%; display: block;text-align: center'>Бажарилган</span>";
                                    }
                                    $datetime2 = date_create($d->deadtime);
                                    $datetime1 = date_create(date('Y-m-d'));

                                    $interval = date_diff($datetime1, $datetime2);
                                    $days = $interval->format('%a ');
                                    $ds = $interval->format('%R%a ');
                                    $class = "";
                                    if($ds <= 5){
                                        $class = "bg-warning";
                                    }
                                    if($ds < 0){
                                        $class = "bg-danger";
                                    }
                                    $res = "<span class='{$class}' style='width: 100%; height: 100%; display: block;text-align: center'>".$d->deadtime.' <br>'.$days.' кун қолди'."</span> ";


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
                            'created',
                            //'status',

//                            ['class' => 'yii\grid\ActionColumn'],
                        ],
                    ]); ?>

                </div>
            </div>
        </div>
    </div>



</div>
