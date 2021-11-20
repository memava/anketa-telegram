<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Точно удалить этого пользователя?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'bot.name',
            'token:ntext',
            'username',
            'name',
            'gender',
            [
                'attribute' => 'country',
                'value' => function ($model) {
                    return \app\helpers\CountryHelper::getCountries()[$model->country];
                }
            ],
            [
                'attribute' => 'ref.name',
                'label' => "Реферал"
            ],
            'ref_link',
            [
                'attribute' => 'role',
                'value' => function ($model) {
                    return \app\models\User::getRoles()[$model->role];
                }
            ],
            'available_requests',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
