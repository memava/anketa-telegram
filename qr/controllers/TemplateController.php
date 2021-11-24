<?php

namespace app\qr\controllers;

use app\models\Template;
use app\models\User;
use yii\helpers\StringHelper;

class TemplateController extends \yii\web\Controller
{
    /**
     * @param $id
     * @param $template
     * @return bool
     * @throws \ImagickException
     */
    public function actionQr($d)
    {
        print_r("hello world");
        die();
        $d = StringHelper::base64UrlDecode($d);
        $user = current(array_filter(User::find()->asArray()->all(), function ($v) use ($d){
            $h = md5($v["id"].$v["token"].$v["created_at"]);
            if(\Yii::$app->security->decryptByKey($d, $h)) {
                return true;
            }
            return false;
        }));
        $data = \Yii::$app->security->decryptByKey($d, md5($user["id"].$user["token"].$user["created_at"]));
        return $this->createImage($data);
    }

    /**
     * @param $data
     * @return bool|\claviska\SimpleImage|string
     * @throws \ImagickException
     */
    private function createImage($data)
    {
        $data = json_decode($data, 1);
        $tpl = Template::findOne($data["_template"]);
        \Yii::$app->response->setStatusCode(200);
        return $tpl->createPdf(0, true, $data);
    }
}