<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Транзакции';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaction-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ["attribute" => "bott", 'value' => 'user.bot.name', "filter" => \yii\helpers\ArrayHelper::map(\app\models\Bot::find()->all(), "id", "name")],
            ['attribute' => 'token', 'value' => function($m) {
                return Html::a($m->user->token, "tg://user?id=".$m->user->token);
            }, 'format' => 'raw'],
            ["attribute" => "user_id", "value" => "user.username"],
            'amount',
            'sum',
            //'currency',
            ['attribute' => 'status', 'value' => function($m) {
                return \app\models\Transaction::getStatuses()[$m->status];
            }, 'filter' => \app\models\Transaction::getStatuses()],
            'created_at:datetime',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
