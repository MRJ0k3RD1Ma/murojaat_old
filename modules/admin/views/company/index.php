<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\CompanySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ташкилотлар';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-index">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">
                        <a href="" class="btn btn-danger" data-method="post"><span class="fa fa-file-excel"></span> Эхпорт</a>
                        <?= Html::a('Ташкилотни туман бўлимлари билан қўшиш', ['createwithdist'], ['class' => 'btn btn-primary']) ?>
                        <?= Html::a('Ташкилот қўшиш', ['create'], ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
                <div class="card-body">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
//                            'name',
                            [
                                'attribute'=>'name',
                                'value'=>function($d){
                                    $url = Yii::$app->urlManager->createUrl(['/admin/company/view','id'=>$d->id]);
                                    return "<a href='{$url}'>{$d->name}</a>";
                                },
                                'format'=>'raw'
                            ],
//            'id',
//                            'management',

                            'inn',
//            'password',
                            'director',
//            'phone',
//            'telegram',
//            'active_to',
//            'active_each',
//            'created',
//            'updated',
//            'region_id',
                            'district_id',
//                            'village_id',
//            'address',
//                            'status',
                            [
                                'attribute'=>'group_id',
                                'value'=>function($d){
                                    return $d->group->name;
                                },
                                'filter'=>\yii\helpers\ArrayHelper::map(\app\models\CompanyGroup::find()->all(),'id','name')
                            ],
                            [
                                'attribute'=>'type_id',
                                'value'=>function($d){
                                    return $d->type->name;
                                },
                                'filter'=>\yii\helpers\ArrayHelper::map(\app\models\CompanyType::find()->where(['group_id'=>$searchModel->group_id])->all(),'id','name')
                            ],

                            ['class' => 'yii\grid\ActionColumn'],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>





</div>
