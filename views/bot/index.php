<?php

use app\models\Bot;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BotSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Боты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bot-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать бота', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            ['attribute' => 'name', 'value' => function(Bot $m) {
                return Html::a($m->name, \yii\helpers\Url::to(["bot/stats", "id" => $m->id]));
            }, 'format' => 'raw'],
            'bot_name',
            'free_requests',
            'requests_for_ref',
            //'payment_system',
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
