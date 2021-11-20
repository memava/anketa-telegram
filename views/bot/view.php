<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Bot */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Боты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="bot-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Статистика', ['stats', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Точно удалить этого бота?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'platform',
            'name',
            'bot_name',
            'token:ntext',
            'free_requests',
            'requests_for_ref',
            [
                'attribute' => 'payment_system',
                'value' => function($m) {
                    return \app\models\Bot::getPaymentSystems()[$m->payment_system];
                }
            ],
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
<pre>
    <?=$model->webhookInfo?>
</pre>
