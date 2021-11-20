<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\KeyboardButton */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="keyboard-button-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'keyboard_id')->dropDownList(\app\models\Keyboard::getKeyboardsTypes()) ?>
    <?= $form->field($model, 'bot_id')->dropDownList(\yii\helpers\ArrayHelper::map(\app\models\Bot::find()->all(), "id", "name")) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'action')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList([\app\models\KeyboardButton::STATUS_ENABLE => "Активна", \app\models\KeyboardButton::STATUS_DISABLE => "Неактивна"]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
