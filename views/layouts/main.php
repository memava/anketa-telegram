<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header>
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top',
        ],
    ]);
    if(Yii::$app->user->isGuest) {
        $items = [
			['label' => 'Login', 'url' => ['/site/login']],
        ];
    } else {
        $items = [
			['label' => 'Боты', 'url' => ['/bot/index']],
			['label' => 'Пользователи', 'url' => ['/user/index']],
			['label' => 'Шаблоны', 'url' => ['/template/index']],
			['label' => 'Клавиатура', 'url' => ['/keyboard/index']],
			['label' => 'Запросы', 'url' => ['/crequest/index']],
			['label' => 'Транзакции', 'url' => ['/transaction/index']],
			['label' => 'Уведомления', 'url' => ['/notification/index']],
            ['label' => 'Конфиг', 'url' => ['/config/index']],
           '<li>'
			   . Html::beginForm(['/site/logout'], 'post', ['class' => 'form-inline'])
			   . Html::submitButton(
				   'Выйти (' . Yii::$app->user->identity->username . ')',
				   ['class' => 'btn btn-link logout']
			   )
			   . Html::endForm()
			   . '</li>'
        ];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => $items,
    ]);
    NavBar::end();
    ?>
</header>

<main role="main" class="flex-shrink-0">
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
