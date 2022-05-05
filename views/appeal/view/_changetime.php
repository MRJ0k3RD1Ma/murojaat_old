<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\request */
/* @var $form ActiveForm */
?>

<div class="form">

    <?php $form = ActiveForm::begin(['action'=>Yii::$app->urlManager->createUrl(['/appeal/sendrequest'])]); ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'type_id')->dropDownList(\yii\helpers\ArrayHelper::map(\app\models\RequestType::find()->all(),'id','name')) ?>

        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'date')->textInput(['type'=>'date']) ?>

        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'file')->fileInput(['class'=>'form-control']) ?>
        </div>
    </div>

    <?= $form->field($model, 'detail')->textarea(['rows'=>6]) ?>

    <div hidden class="hidden">

        <?= $form->field($model, 'sender_id') ?>
        <?= $form->field($model, 'reciever_id') ?>
        <?= $form->field($model, 'register_id') ?>
        <?= $form->field($model, 'appeal_id') ?>
        <?= $form->field($model, 'status_id') ?>

    </div>
    <div class="form-group">
        <?= Html::submitButton('Saqlash', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div><!-- _form -->
