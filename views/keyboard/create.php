<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\KeyboardButton */

$this->title = 'Создать кнопку';
$this->params['breadcrumbs'][] = ['label' => 'Кнопки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="keyboard-button-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
