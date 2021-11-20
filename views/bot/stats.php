<?php

use app\models\User;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BotSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $bot \app\models\Bot */

$this->title = 'Статистика по боту: '.$bot->name;
$this->params['breadcrumbs'][] = ['label' => 'Боты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $bot->name, 'url' => ['view', 'id' => $bot->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bot-index">

	<h1><?= Html::encode($this->title) ?></h1>
	<div class="row col-sm-12">
		<span class="font-weight-bold">Всего подписчиков: </span>&nbsp;<span><?=$bot->subscribersCount?></span>
	</div>
	<div class="row col-sm-12">
		<span class="font-weight-bold">Новые сегодня (с начала суток): </span>&nbsp;<span><?=$bot->subscribersDayCount?></span>
	</div>
	<div class="row col-sm-12">
		<span class="font-weight-bold">Запросов всего: </span>&nbsp;<span><?=$bot->requestCount?></span>
	</div>
	<div class="row col-sm-12">
		<span class="font-weight-bold">Запросов сегодня: </span>&nbsp;<span><?=$bot->requestDayCount?></span>
	</div>
	<div class="row col-sm-12">
		<span class="font-weight-bold">Нажатия на донат всего: </span>&nbsp;<span><?=$bot->clicksDonateCount?></span>
	</div>
	<div class="row col-sm-12">
		<span class="font-weight-bold">Нажатия на донат сегодня: </span>&nbsp;<span><?=$bot->clicksDonateDayCount?></span>
	</div>
	<div class="row col-sm-12">
		<span class="font-weight-bold">Нажатия на оплату всего: </span>&nbsp;<span><?=$bot->clicksPayCount?></span>
	</div>
	<div class="row col-sm-12">
		<span class="font-weight-bold">Нажатия на оплату всего: </span>&nbsp;<span><?=$bot->clicksPayDayCount?></span>
	</div>
    <div class="row col-sm-12">
		<span class="font-weight-bold">Общий заработок: </span>&nbsp;<span><?=$bot->usersSumPaid?></span>
	</div>

    <div class="row col-sm-12">
        <div class="col-sm-12">
            <div class="row">
                <span class="font-weight-bold">Топ 20 рефводов за все время</span>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="row">
				<?php
				foreach ($bot->topRefs(20, false) as $ref) {
					$model = $ref["model"];
					/**
					 * @var User $model
					 */
					echo "@{$model->username} ({$model->name}, {$model->token}) привел ".$ref["count"] . " человек.<br/>";
				}
				?>
            </div>
        </div>
	</div>

    <div class="row col-sm-12">
        <div class="col-sm-12">
            <div class="row">
                <span class="font-weight-bold">Топ 20 рефводов за все время</span>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="row">
				<?php
				foreach ($bot->topRefs(20, true) as $ref) {
					$model = $ref["model"];
					/**
					 * @var User $model
					 */
					echo "@{$model->username} ({$model->name}, {$model->token}) привел ".$ref["count"] . " человек.<br/>";
				}
				?>
            </div>
        </div>
    </div>

</div>
