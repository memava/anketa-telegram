
<?php
/**
 * @var \app\models\Config $model
 * @var string $variable
 */
$form = \yii\widgets\ActiveForm::begin(["action" => \yii\helpers\Url::to(["config/save"])]); ?>
<?=$form->field($model, 'variable')->textInput(["value" => $variable, "disabled" => true])->label(false)?>
<?=$form->field($model, 'variable')->hiddenInput(["value" => $variable])->label(false)?>
<?=$form->field($model, 'value')->textarea()->label(false)?>
<?=\yii\bootstrap4\Html::submitButton("Сохранить", ["class" => "btn btn-success"])?>
<?php $form = \yii\widgets\ActiveForm::end(); ?>
