<?php
/**
 * @var \yii\web\View $this
 * @var Config[] $models
 */

use app\helpers\CountryHelper;
use app\models\Config;
use yii\helpers\Url;

$countries = CountryHelper::getCountries();

?>

<div class="navbar navbar-light navbar-expand-md">
    <ul class="navbar-nav ">
        <li class="nav-item <? if(!$cc) echo 'active' ?>">
            <a href="<?= Url::to(['/config/index'])?>" class="nav-link">Общие</a>
        </li>
        <?php
        foreach ($countries as $cid => $country) {
            ?>
            <li class="nav-item <? if($cid == $cc) echo 'active'?>">
                <a href="<?=Url::to(['/config/index', 'country_id' => $cid])?>" class="nav-link"><?= $country ?></a>
            </li>
        <?php } ?>
    </ul>
</div>
<?php foreach ($models as $model) { ?>
    <div class="row mb-2">
        <div class="col-sm-12">
            <?= $this->render('form', ["model" => $model, "variable" => $model->variable]) ?>
        </div>
    </div>
<?php } ?>
