<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\AppealAnswerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Жавоби келган мурожаатлар';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="appeal-answer-index">

    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-body">

                    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                    <div class="table-responsive">

                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],

                                'id',
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
                                [
                                    'attribute'=>'bajaruvchi_id',
                                    'value'=>function($d){
                                        return $d->bajaruvchi->company->name;
                                    },
                                    'filter'=>false,
                                ],
                                'preview',
//                            'detail:ntext',
                                [
                                    'attribute'=>'detail',
                                    'value'=>function($d){
                                        return mb_substr($d->detail,0,100);
                                    }
                                ],
                                [
                                    'label'=>'Рақами ва санаси',
                                    'attribute'=>'number',
                                    'value'=>function($d){
                                        return $d->number."<br>".$d->date;
                                    },
                                    'format'=>'raw'
                                ],
//                            'tarqatma_number',
//                            'tarqatma_date',
                                //'bajaruvchi_id',
                                //'reaply_send',
                                'name',
//                            'file',
                                [
                                    'attribute'=>'file',
                                    'value'=>function($d){
                                        if($d->file){
                                            return "<a href='/uploads/{$d->file}'>Файлни юклаш</a>";
                                        }
                                        return null;
                                    },
                                    'format'=>'raw'
                                ],
//                            'status',
                                'status_boshqa',
                                'created',
                                //'updated',

//                            ['class' => 'yii\grid\ActionColumn'],
                            ],
                        ]); ?>

                    </div>

                </div>
            </div>
        </div>
    </div>



</div>
