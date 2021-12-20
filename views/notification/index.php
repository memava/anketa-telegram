<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NotificationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Уведомления';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notification-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать уведомление', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'text',
            [
                "attribute" => "bot_id",
                "value" => function(\app\models\Notification $m) {
                    return \app\models\Notification::getBots()[$m->bot_id];
                },
                "filter" => \app\models\Notification::getBots()
            ],
            [
                "attribute" => "type",
                "value" => function(\app\models\Notification $m) {
                    return \app\models\Notification::getTypes()[$m->type];
                },
                'filter' => \app\models\Notification::getTypes()
            ],
            'condition_value',
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
