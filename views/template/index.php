<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TemplateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Шаблоны';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="template-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать шаблон', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'country',
                'value' => function ($m) {
                    return \app\helpers\CountryHelper::getCountries()[$m->country];
                },
                'filter' => \app\helpers\CountryHelper::getCountries()
            ],
            [
                'attribute' => 'language',
                'value' => function ($m) {
                    return \app\models\CRequest::LANGUAGES[$m->language];
                },
                'filter' => \app\models\CRequest::LANGUAGES
            ],
            'name',
            'slug',
            'domain',
            //'template',
            //'data:ntext',

            [
                'class' => 'yii\grid\ActionColumn',
            ],
        ],
    ]); ?>


</div>
