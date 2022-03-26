<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\AppealBajaruvchiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Appeal Bajaruvchis';
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

//                            'id',
//                            'company_id',
//                            'appeal_id',
//                            'register_id',
                            [
                                 'label'=>'',
                                'value'=>function($d){
                                    $url = Yii::$app->urlManager->createUrl(['/appeal/regform','id'=>$d->id]);
                                    return "<button class='btn btn-link btnregister' type='button' value='{$url}'>Рўйхатга олиш</button>";
                                },
                                'format'=>'raw'
                            ],
                            [
                                'attribute'=>'register_id',
                                'label'=>'Ташкилот номи, мурожаат рақами ва санаси',
                                'value'=>function($d){
                                    $reg = $d->register;
                                    return $reg->company->name.' <br>№'.$reg->number.' '.$reg->date;
                                },
                                'format'=>'raw'
                            ],
                            'deadline',
                            'deadtime',
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

    <div id="myModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Мурожаатни рўйхатга олиш</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Loading...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Ёпиш</button>
                </div>
            </div>
        </div>

<?php
    $this->registerJs("
        $('.btnregister').click(function(){
            var id = this.value;
            $('#myModal').modal('show').find('.modal-body').load(id);
        })
    ")
?>