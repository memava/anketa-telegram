<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Bot */

$this->title = 'Изменить: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Боты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="bot-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
