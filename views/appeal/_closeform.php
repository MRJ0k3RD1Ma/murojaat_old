<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AppealAnswer */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="appeal-answer-form">

    <?php $form = ActiveForm::begin(['action'=>Yii::$app->urlManager->createUrl(['/appeal/close','id'=>$model->register_id])]); ?>

    <?= $form->field($model, 'n_olish')->dropDownList(\yii\helpers\ArrayHelper::map(\app\models\AppealControl::find()->where(['>','id',1])->all(),'id','name'),['prompt'=>'Назоратдан олиш']) ?>

    <?= $form->field($model, 'preview')->textInput(['maxlength' => true]) ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'number')->textInput(['maxlength' => true]) ?>

        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'date')->textInput(['type'=>'date']) ?>
        </div>
    </div>


    <?= $form->field($model, 'detail')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'file')->fileInput() ?>

    <?= $form->field($model, 'reaply_send')->checkbox(['value'=>1]) ?>

    <div class="form-group">
        <?= Html::submitButton('Жўнатиш', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
