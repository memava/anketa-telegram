<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Notification */

$this->title = 'Изменить уведомление: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Уведомления', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="notification-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
