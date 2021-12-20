<?php

use app\models\Bot;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Bot */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bot-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'platform')->dropDownList(Bot::getPlatforms()) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bot_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'token')->textarea(['rows' => 1]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
