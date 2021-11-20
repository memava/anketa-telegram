<?php
/**
 * @var \yii\web\View $this
 * @var Config[] $models
 */

use app\models\Config;

?>
<?php foreach ($models as $model) { ?>
	<div class="row mb-2">
		<div class="col-sm-12">
			<?=$this->render('form', ["model" => $model, "variable" => $model->variable])?>
		</div>
	</div>
<?php } ?>
