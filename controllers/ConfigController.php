<?php
/**
 *
 * pcr-bot 2021
 */

namespace app\controllers;

use app\models\Config;

class ConfigController extends \yii\web\Controller
{
	/**
	 * @return string
	 */
	public function actionIndex()
	{
		$models = Config::find()->all();
		return $this->render('index', ["models" => $models]);
	}

	public function actionSave()
	{
		$config = Config::findOne(["variable" => \Yii::$app->request->post('Config')["variable"]]);
		$config->value = \Yii::$app->request->post('Config')["value"];
		$config->save(false);
		return $this->redirect(["config/index"]);
	}
}