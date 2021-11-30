<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "config".
 *
 * @property string|null $variable
 * @property string|null $value
 * @property string|null $comment
 */
class Config extends \yii\db\ActiveRecord
{
	const VAR_QIWI_PUBLIC_KEY = "qiwi_public_key";
	const VAR_QIWI_PRIVATE_KEY = "qiwi_private_key";

	const VAR_PAYS_PUBLIC_KEY = "pays_public_key";
	const VAR_PAYS_PRIVATE_KEY = "pays_private_key";
    const VAR_DEFAULT_DESCRIPTION_BOTS = 'default_description';
	const VAR_GLOBAL24_KEY = "global24_key";

	const VAR_CLOUDFLARE_KEY = "cloudflare_key";
	const VAR_CLOUDFLARE_EMAIL = "cloudflare_email";
	const VAR_CLOUDFLARE_ZONE = "cloudflare_zone_ident";

	const VAR_TEXT_NO_REQUESTS = "text_no_requests";
	const VAR_TEXT_STEP_ONE = "text_step_one";
	const VAR_TEXT_STEP_TWO = "text_step_two";
	const VAR_TEXT_STEP_THREE = "text_step_three";
	const VAR_TEXT_STEP_FOUR = "text_step_four";
	const VAR_TEXT_STEP_FIVE = "text_step_five";
	const VAR_TEXT_STEP_SIX = "text_step_sxi";
	const VAR_TEXT_STEP_SEVEN = "text_step_seven";
	const VAR_TEXT_BEFORE_MAKE = "text_before_make";
	const VAR_TEXT_DONATE = "text_donate";
	const VAR_TEXT_NO_TEMPLATES = "text_no_templates";
	const VAR_TEXT_AFTER_MAKE = "text_after_make";
    const VAR_TEXT_NO_CREATE_REQUEST = 'text_no_create_request';
	const VAR_TEXT_WEB_START = "text_web_start";
	const VAR_TEXT_WEB_APIKEY = "text_web_apikey";
	const VAR_TEXT_WEB_NAME = "text_web_name";
	const VAR_TEXT_WEB_AFTER_CREATE = "text_web_after_create";

	const VAR_TEXT_RESERVE = "text_reserve";


	const VAR_DEFAULT_BUTTONS = "default_buttons";

	const VAR_HM_API_KEY = "hm_api_key";

	const VAR_DEFAULT_RESERVE_BOT = "default_reserve_bot";
    const VAR_DEFAULT_LINK_TEXT = "default_link_text";
	const VAR_ADMIN_PASSWORD = "admin_password";

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'config';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['value', 'comment'], 'string'],
            [['variable'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'variable' => 'Variable',
            'value' => 'Value',
        ];
    }

	/**
	 * @param $variable
	 * @return string|null
	 */
	public static function get($variable)
	{
		$c = self::findOne(["variable" => $variable]);
		return $c ? $c->value : null;
	}
}
