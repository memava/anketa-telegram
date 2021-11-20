<?php
/**
 *
 * crequest-bot 2021
 */

namespace app\events;

use app\models\Template;
use app\models\CRequest;
use app\models\User;
use yii\base\Event;

class CRequestMadeEvent extends Event
{
	/**
	 * @var User $user
	 */
	public $user;

	/**
	 * @var CRequest
	 */
	public $request;

	/**
	 * @var Template[]
	 */
	public $templates;

}