<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'bot_id') ?>

    <?= $form->field($model, 'token') ?>

    <?= $form->field($model, 'username') ?>

    <?= $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'gender') ?>

    <?php // echo $form->field($model, 'country') ?>

    <?php // echo $form->field($model, 'ref_id') ?>

    <?php // echo $form->field($model, 'ref_link') ?>

    <?php // echo $form->field($model, 'role') ?>


    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
