<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\KeyboardButtonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Кнопки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="keyboard-button-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать кнопку', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            ['attribute' => 'keyboard_id',
                'value' => function($m) { return \app\models\Keyboard::getKeyboardsTypes()[$m->keyboard_id];},
                'filter' => \app\models\Keyboard::getKeyboardsTypes()
            ],
            'bot.name',
            'name',
            'action',
			['attribute' => 'status',
				'filter' => [\app\models\KeyboardButton::STATUS_DISABLE => "Неактивна", \app\models\KeyboardButton::STATUS_ENABLE => "Активна"]
			],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
