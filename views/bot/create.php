<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Bot */

$this->title = 'Создать бота';
$this->params['breadcrumbs'][] = ['label' => 'Боты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bot-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
