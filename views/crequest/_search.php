<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CRequestSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="request-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'bot_id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'unique_id') ?>

    <?= $form->field($model, 'city') ?>

    <?php // echo $form->field($model, 'language') ?>

    <?php // echo $form->field($model, 'fio') ?>

    <?php // echo $form->field($model, 'gender') ?>

    <?php // echo $form->field($model, 'birthday') ?>

    <?php // echo $form->field($model, 'slug') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
