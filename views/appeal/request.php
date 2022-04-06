<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\DeadlineChangesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Муддат узайтиришга сўровлар';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deadline-changes-index">

    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-body">

                    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

//                            'id',
                            [
                                'label'=>'Рақаси ва санаси',
                                'value'=>function($d){
                                    return "<b>№ {$d->register->parent->register->number}</b> <br> {$d->register->parent->register->date}";
                                },
                                'format'=>'raw'
                            ],
                            [
                                'label'=>'Мурожаатчи',
                                'attribute'=>'appeal_id',
                                'value'=>function($d){

                                    if($q = $d->appeal->question){
                                        $res = $q->group->code.'-'.$q->code.'.'.$q->name;
                                    }else{
                                        $res = "Савол белгиланмаган";
                                    }

                                    $url = Yii::$app->urlManager->createUrl(['/appeal/view','id'=>$d->register->parent->register->id]);

                                    $res = $d->appeal->person_name.'<br>'.$res;
                                    return "<a href='{$url}'>{$res}</a>";
                                },
                                'format'=>'raw',
                                'filter'=>false
                            ],
                            'comment',
                            'file',
//                            'appeal_id',
//                            'register_id',
                            'deadline',
//                            'status_id',
                            [
                                'attribute'=>'status_id',
                                'value'=>function($d){return $d->status->name;},
                                'filter'=>\yii\helpers\ArrayHelper::map(\app\models\DeadlineStatus::find()->all(),'id','name')
                            ],
                            //'ads',
                            'created',
                            //'updated',

                            ['class' => 'yii\grid\ActionColumn'],
                        ],
                    ]); ?>

                </div>
            </div>
        </div>
    </div>



</div>
