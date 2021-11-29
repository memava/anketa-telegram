<?php

use kartik\file\FileInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Template */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="template-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'country')->dropDownList(\app\helpers\CountryHelper::getCountries()) ?>
    <?= $form->field($model, 'language')->dropDownList(\app\models\CRequest::LANGUAGES) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'domain')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'uTemplate')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*'],
        'pluginOptions' => [
            'initialPreview' => ($model->isNewRecord || !$model->slug) ? false : "/uploads/".$model->slug ."_template.jpg",
            'initialPreviewAsData' => true,
            'showPreview' => true,
            'showRemove' => false,
            'showUpload' => false
        ],
    ]) ?>

    <div class="row mb-2">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <span>
                                Доступные параметры:<br/>
                                <b>fio</b> - ФИО<br/>
                                <b>birthday</b> - дата рождения<br/>
                                <b>testDate</b> - дата запроса<br/>
                                <b>date</b> - дата заказа запроса<br/>
                                <b>resultDate</b> - дата выдачи результата запроса<br/>
                                <b>number</b> - номер запроса<br/>
                                <b>testId</b> - номер запроса (рандомные 9 цифр)<br/>
                                <b>city</b> - город<br/>
                                <b>gender</b> - пол<br/>
                                <b>age</b> - возраст<br/>
                                <b>qr</b> - QR код<br/>
                            </span>
                        </div>
                        <div class="col-sm-6">
                            <span>
                                Шаблон заполнения (параметров может быть сколько угодно, но каждый с новой строки, шрифт - default, bold):<br/>
                                <b>параметр:Х:Y:font:size</b><br/>
                                <b>параметр:Х:Y:font:size</b><br/>
                                <b>параметр1,параметр2,свой текст:Х:Y:font:size</b><br/>
                            </span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <?=$form->field($model, "data")->textarea(["rows" => 8])?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php if(!$model->isNewRecord) { ?>
	<?=Html::beginForm(["template/preview", "id" => $model->id])?>
	<?= Html::submitButton('Предпросмотр', ['class' => 'btn btn-info']) ?>
	<?=Html::endForm()?>
    <?php } ?>

</div>
