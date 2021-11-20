<?php

use app\models\CRequest;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CRequestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $templates \app\models\Template */

$this->title = 'Запросы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="request-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                'attribute' => 'bot_id',
				'value' => function (CRequest $m) {
					return Html::a($m->bot->name, ["bot/view", "id" => $m->bot_id]);
				},
                'filter' => \yii\helpers\ArrayHelper::map(\app\models\Bot::find()->all(), "id", "name"),
                'format' => 'raw'
            ],
            [
                'attribute' => 'user_id',
                'value' => function (CRequest $m) {
                    $username = $m->user ? $m->user->username : '';
                    $token = $m->user ? $m->user->token : '';
                    return Html::a($username . " " . $token, ["user/view", "id" => $m->user_id]);
                },
                'format' => 'raw'
            ],
//            'unique_id',
            'city',
            //'language',
            'fio',
            //'gender',
            //'birthday',
            //'slug',
            [
                'attribute' => 'status',
                'value' => function ($m) {
                    return CRequest::getStatuses()[$m->status];
                },
                'filter' => CRequest::getStatuses()
            ],
            'created_at:datetime',
            'updated_at:datetime',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete} {make}',
                'buttons' => [
                        'make' => function($url, CRequest $model, $id) use($templates) {
                            if($model->status != CRequest::STATUS_ACTIVE) return '';
                            foreach ($templates as $template) {
								/**
								 * @var $template \app\models\Template
								 */
								$items[] = ["label" => $template->name, "url" => \yii\helpers\Url::to(["template/qr", "id" => $model->id, "template" => $template->id])];
                            }
                            return \yii\bootstrap4\ButtonDropdown::widget([
                                "label" => FA::icon(FA::_PLUS_SQUARE),
                                "dropdown" => [
                                        "items" => $items
                                ],
                                'encodeLabel' => false
                            ]);
                        }
                ]
            ],
        ],
    ]); ?>


</div>
