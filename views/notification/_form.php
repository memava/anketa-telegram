<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Notification */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="notification-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'text')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bot_id')->dropDownList(\app\models\Notification::getBots()) ?>

    <?= $form->field($model, 'type')->dropDownList(\app\models\Notification::getTypes()) ?>

    <?= $form->field($model, 'condition_type')->dropDownList(\app\models\Notification::getConditions()) ?>

    <?= $form->field($model, 'condition_value')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
