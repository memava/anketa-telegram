<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'botname',
                'value' => 'bot.name'
            ],
            'token:ntext',
            'username',
            'name',
            //'gender',
            //'country',
            //'ref_id',
            'ref_link',
            [
                'attribute' => 'role',
                'filter' => \app\models\User::getRoles(),
                'value' => function($model){
                    return \app\models\User::getRoles()[$model->role];
                }
            ],
            'available_requests',
            'created_at:datetime',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
