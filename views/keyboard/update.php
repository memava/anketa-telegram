<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\KeyboardButton */

$this->title = 'Изменить кнопку: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Кнопки', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="keyboard-button-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
