<?php

use app\models\Bot;
use kartik\file\FileInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Bot */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bot-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'platform')->dropDownList(Bot::getPlatforms()) ?>
    <?= $form->field($model, 'type')->dropDownList(Bot::getTypes()) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bot_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'token')->textarea(['rows' => 1]) ?>

    <?= $form->field($model, 'free_requests')->textInput() ?>

    <?= $form->field($model, 'requests_for_ref')->textInput() ?>

    <?= $form->field($model, 'payment_system')->dropDownList(Bot::getPaymentSystems()) ?>

	<?= $form->field($model, 'message_after_request_if_no_requests')->textarea(['rows' => 3]) ?>


    <?= $form->field($model, 'custom_description')->textarea(['rows' => 3]) ?>

    <?= $form->field($model, 'uImage')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*'],
        'pluginOptions' => [
            'initialPreview' => ($model->isNewRecord || !$model->image) ? false : "/uploads/".$model->image,
            'initialPreviewAsData' => true,
            'showPreview' => true,
            'showRemove' => true,
            'showUpload' => false
        ],
    ])  ?>
	<?= $form->field($model, 'reserve_bot')->textInput() ?>

    <?php foreach (\app\helpers\CountryHelper::getCountries() as $k => $country) {
        echo $form->field($model, 'country_'.$k)->checkbox(['label' => $country]);
    }?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
