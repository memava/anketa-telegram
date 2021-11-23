<?php
/**
 * @var \yii\web\View $this
 */
\yii\bootstrap4\BootstrapAsset::register($this);
$this->beginPage();
?>
<html>
<body>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="text-success text-center h1">
Оплата прошла успешно!
Перейдите обратно в бот.
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
