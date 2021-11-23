<?php
/**
 * @var \yii\web\View $this
 * @var \app\models\Message $model
 */

use app\models\Bot;
use yii\helpers\ArrayHelper;
$this->title = 'Отправить сообщение';
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$form = \yii\bootstrap4\ActiveForm::begin();
?>

<div class="row">
    <div class="col-sm-8">
        <?=$form->field($model, "bot")->dropdownList([0 => "Всем"] + ArrayHelper::map(Bot::find()->all(), "id", "name"))?>
    </div>
    <div class="col-sm-2">
        <?=$form->field($model, "offset")?>
    </div>
    <div class="col-sm-2">
        <?=$form->field($model, "limit")?>
    </div>
</div>
<?=$form->field($model, "message")->textarea()?>
<?=$form->field($model, "image")->fileInput()?>
<?=$form->field($model, "oneMessage")->checkbox()?>
<?=\yii\bootstrap4\Html::submitButton("Отправить", ["class" => "btn btn-success"])?>
<?php \yii\bootstrap4\ActiveForm::end(); ?>