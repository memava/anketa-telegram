<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'token')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'gender')->textInput() ?>

    <?= $form->field($model, 'country')->dropDownList(\app\helpers\CountryHelper::getCountries()) ?>

    <?= $form->field($model, 'ref_id')->textInput() ?>

    <?= $form->field($model, 'ref_link')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'role')->dropDownList(\app\models\User::getRoles()) ?>

    <?= $form->field($model, 'available_requests')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
