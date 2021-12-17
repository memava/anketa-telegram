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
    
<?= Html::a('Оплаченые', ['/transaction/index?TransactionSearch%5Bid%5D=&TransactionSearch%5Bpayment_system%5D=&TransactionSearch%5Bbott%5D=&TransactionSearch%5Btoken%5D=&TransactionSearch%5Buser_id%5D=&TransactionSearch%5Bsum%5D=&TransactionSearch%5Bstatus%5D=10&TransactionSearch%5Bcreated_at%5D=&TransactionSearch%5Bupdated_at%5D=&sort=-updated_at'], ['class'=>'btn btn-primary']) ?>
    
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['attribute' => 'id', 'value' => function($m) {
                return $m->id . " (".$m->unique_id.")";
            }],
            ["attribute" => "payment_system", "value" => function($m) {
                return \app\models\Bot::getPaymentSystems()[$m->payment_system] ?? null;
            }, 'filter' => \app\models\Bot::getPaymentSystems()],
            ["attribute" => "bott", 'value' => 'user.bot.name', "filter" => \yii\helpers\ArrayHelper::map(\app\models\Bot::find()->all(), "id", "name")],
            ['attribute' => 'token', 'value' => function($m) {
                return Html::a($m->user->token, "/user/update?id=".$m->user->id);
            }, 'format' => 'raw'],
            ["attribute" => "user_id", "value" => "user.username"],
            ["attribute" => "country", "value" => "user.country", 'label' => 'Страна', 'filter' => \app\helpers\CountryHelper::getCountries()],
            'amount',
            'sum',
            //'currency',
            ['attribute' => 'status', 'value' => function($m) {
                return \app\models\Transaction::getStatuses()[$m->status];
            }, 'filter' => \app\models\Transaction::getStatuses()],
            'created_at:datetime',
            'updated_at:datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
