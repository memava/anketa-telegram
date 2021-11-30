<?php
/**
 *
 * pcr-bot 2021
 */

namespace app\controllers;

use app\models\Config;
use app\models\Transaction;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

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

    /**
     * @return \yii\web\Response
     */
	public function actionSave()
	{
		$config = Config::findOne(["variable" => \Yii::$app->request->post('Config')["variable"]]);
        if($config->load(\Yii::$app->request->post())) {
            $config->uFile = UploadedFile::getInstance($config, "uFile");
            if($config->upload() && $config->save(false)) {
                return $this->redirect(["config/index"]);
            }
        }
	}

    /**
     * @param $var
     * @return void|\yii\web\Response
     */
    public function actionClear($var)
    {
        $config = Config::findOne($var);
        if($config->clear()) {
            return $this->redirect(["config/index"]);
        }
    }
}