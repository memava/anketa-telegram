<?php
/**
 *
 * pcr-bot 2021
 */

namespace app\controllers;

use app\models\Config;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class ConfigController extends \yii\web\Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ]
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

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