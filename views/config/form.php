<?php
/**
 * @var Config $model
 * @var string $variable
 */

use app\models\Config;
use kartik\file\FileInput;
use yii\bootstrap4\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin(["action" => \yii\helpers\Url::to(["config/save"]), 'options' => ['enctype' => 'multipart/form-data']]); ?>
<?= $form->field($model, 'variable')->textInput(["value" => $variable, "disabled" => true])->label($model->comment ?: false) ?>
<?= $form->field($model, 'variable')->hiddenInput(["value" => $variable])->label(false) ?>
<?= ($model->type == Config::TYPE_STRING || !$model->type) ? $form->field($model, 'value')->textarea()->label(false) : $form->field($model, 'uFile')->label(false)->widget(FileInput::classname(), [
    'options' => ['accept' => 'image/*'],
    'pluginOptions' => [
        'initialPreview' => ($model->isNewRecord || !$model->value) ? false : "/uploads/" . $model->value,
        'initialPreviewAsData' => true,
        'showPreview' => true,
        'showRemove' => false,
        'showUpload' => false
    ],
]) ?>
<?= Html::submitButton("Сохранить", ["class" => "btn btn-success"]) ?>
<?= Html::a("Очистить", ["config/clear", "var" => $model->variable], ["class" => "btn btn-danger"]) ?>
<?php $form = ActiveForm::end(); ?>
