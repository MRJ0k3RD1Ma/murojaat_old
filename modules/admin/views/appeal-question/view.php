<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\AppealQuestion */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Appeal Questions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="appeal-question-view">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">
                        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                        ],
                        ]) ?>
                    </div>
                </div>
                <div class="card-body">

                    <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                                'id',
            'group_id',
            'code',
            'name',
                    ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>


</div>
