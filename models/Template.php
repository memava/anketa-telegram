<?php

namespace app\models;

use Base32\Base32;
use Cloudflare\Api;
use Cloudflare\Zone;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeNone;
use Endroid\QrCode\Writer\PngWriter;
use Yii;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\web\UploadedFile;

/**
 * This is the model class for table "template".
 *
 * @property int $id
 * @property int|null $country
 * @property int|null $language
 * @property string|null $name
 * @property string|null $slug
 * @property string|null $domain
 * @property string|null $template
 * @property UploadedFile $uTemplate
 * @property string|null $data
 */
class Template extends \yii\db\ActiveRecord
{
	const FONT_DEFAULT = 'default';
	const FONT_BOLD = 'bold';
	const FONT_COUR = 'cour';
	const FONT_COUR_BOLD = 'courb';
	const FONT_TNR = 'tnr';
	const FONT_TNR_BOLD = 'tnrb';

	const QUALITY_IMAGE = 80;
	const QUALITY_PDF = 60;

	public $uTemplate;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'template';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['country', 'language'], 'integer'],
			[['data'], 'string'],
			[['name', 'slug', 'domain', 'template'], 'string', 'max' => 255],
			["uTemplate", "file"]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'country' => 'Страна',
			'name' => 'Название',
			'slug' => 'Название для документа',
			'domain' => 'Домен',
			'template' => 'Шаблон',
			'data' => 'Data',
			'language' => "Язык"
		];
	}

	/**
	 * @return bool
	 */
	public function upload()
	{
		if(!$this->uTemplate) return true;

		$name = $this->slug."_template.".$this->uTemplate->extension;
		$this->uTemplate->saveAs(Yii::getAlias('@app/web/uploads/' . $name));
		$this->template = $name;
		return true;
	}

	/**
	 * @return array|null
	 */
	public function parseData()
	{
		if($this->data) {
			$params = [];
			$data = explode(PHP_EOL, trim($this->data));
			if($data) {
				foreach ($data as $datum) {
					$datum = implode(";;", explode('\:', $datum));
					$d = explode(":", $datum);
					$param = str_replace([";;"], [":"], $d[0]);
					$x = $d[1];
					$y = $d[2];
					$font = $d[3];
					$size = $d[4];
					$params[] = [
						"param" => $param,
						"x" => (int) $x,
						"y" => (int) $y,
						"font" => $font,
						"size" => (int) $size,
						"params" => array_slice($d, 5)
					];
				}
				return $params;
			}
		}
		return null;
	}

	/**
	 * @param $id
	 * @param bool $toScreen
	 * @return \claviska\SimpleImage|false|string
	 * @throws \ImagickException
	 */
	public function createPdf($id, $toScreen = false, $params = [])
	{
		$tpl = file_exists(Yii::getAlias("@app/web/uploads/" . $this->slug . "_template.jpg")) ? Yii::getAlias("@app/web/uploads/" . $this->slug . "_template.jpg") : Yii::getAlias("@app/web/uploads/" . $this->template);
		$template = $tpl;

		$image = new \claviska\SimpleImage();
		$image->fromFile($template);

		if(!$params) {
			if ($id) {
				$request = CRequest::findOne($id);
			} else {
				$request = new CRequest();
				$request->id = 7777777;
				$request->fio = "Пупок Василий Павлович";
				$request->unique_id = 123123123;
				$request->request_date = "16.11.2021";
				$request->birthday = "12.05.2000";
				$request->city = "Кривой Рог";
				$request->slug = rand(100000000, 999999999);
				$request->gender = 0;
				$request->passport = "TT123123FF";
				$request->inn = "A54235";
				$request->language = 1;
				$request->s_status = 0;
			}

			if(!$toScreen) {
				$filename = $this->slug . $request->unique_id;
			}

			$params = $request->getParams();

			$params["_template"] = $this->id;
		}
		foreach ($this->parseData() as $data) {
			$f = $this->getFontPath($data["font"]);

			if($data["param"] == 'qr') {
				$writer = new PngWriter();
				$qr = QrCode::create($this->shortUrl($this->getFullLink()."?d=".$this->makeQrData($params)))
					->setSize($data["size"])
					->setRoundBlockSizeMode(new RoundBlockSizeModeNone())
					->setMargin(0);
				$res = $writer->write($qr);
				$image->overlay($res->getDataUri(), "top left", 1, $data["x"], $data["y"]);
			} else {
				if(isset($params[$data["param"]])) {
					$text = $data["params"] ? $data["params"][$params[$data["param"]]] : $params[$data["param"]];
					$image->text($text, [
						"anchor" => "top left",
						"size" => $data["size"],
						"fontFile" => $f,
						"xOffset" => $data["x"],
						"yOffset" => $data["y"],
					]);
				} else if($ex = explode(",", $data["param"])) {
					$string = '';
					foreach ($ex as $item) {
						//$item = trim($item);
						if(isset($params[trim($item)])) {
							$text = $data["params"] ? $data["params"][$params[$item]] : $params[$item];
							$string .= $text;
						} else {
							$string .= $item;
						}
					}
					$image->text($string, [
						"anchor" => "top left",
						"size" => $data["size"],
						"fontFile" => $f,
						"xOffset" => $data["x"],
						"yOffset" => $data["y"],
					]);
				} else {
					$image->text($data["param"], [
						"anchor" => "top left",
						"size" => $data["size"],
						"fontFile" => $f,
						"xOffset" => $data["x"],
						"yOffset" => $data["y"],
					]);
				}
			}
		}
		if(!$toScreen) {
			$pdf = new \Imagick();
			$pdf->readImageBlob($image->toString(null, self::QUALITY_IMAGE));
			$pdf->setFormat('pdf');
			$pdf->setSize($image->getHeight(), $image->getWidth());
			$pdf->setImageCompression(\Imagick::COMPRESSION_JPEG);
			$pdf->setImageCompressionQuality(self::QUALITY_PDF);

			$path = Yii::getAlias("@app/web/uploads/" . $filename . ".pdf");
			if ($pdf->writeImage($path)) {
				return $path;
			}
		} else {
			return $image->toScreen();
		}
		return true;
	}

	/**
	 * @param $font
	 * @return false|string
	 */
	private function getFontPath($font)
	{
		switch ($font) {
			case self::FONT_DEFAULT: return Yii::getAlias("@app/web/css/font_1.ttf");
			case self::FONT_BOLD: return Yii::getAlias("@app/web/css/font_1_bold.ttf");
			case self::FONT_COUR: return Yii::getAlias("@app/web/css/cour.ttf");
			case self::FONT_COUR_BOLD: return Yii::getAlias("@app/web/css/couriernewbold.ttf");
			case self::FONT_TNR: return Yii::getAlias("@app/web/css/tnr.ttf");
			case self::FONT_TNR_BOLD: return Yii::getAlias("@app/web/css/tnrb.ttf");
			default: return Yii::getAlias("@app/web/css/font_1.ttf");
		}
	}

	/**
	 * @return string
	 */
	public function getFullLink()
	{
		$ex = explode(".", Yii::$app->request->hostName);
		$domain = $ex[count($ex)-2] . "." . $ex[count($ex)-1];

		if($this->domain) {
			return "https://".$this->domain . "." . $domain . "/";
		} else {
			return "https://".Yii::$app->request->hostName . "/template/qr/";
		}
	}

	/**
	 * @param $data
	 * @return string
	 */
	public function makeQrData($data)
	{
		$encoded = json_encode($data);
		$encoded = Yii::$app->security->encryptByKey($encoded, Config::get(Config::VAR_ENCRYPT_KEY));
//		$encoded = Yii::$app->security->encryptByKey($encoded, md5($data["_userId"].$data["_userToken"].$data["_userCreatedAt"]));
		return StringHelper::base64UrlEncode($encoded);
	}

	/**
	 * @param $url
	 * @return false|string
	 */
	public function shortUrl($url)
	{
		$d = [
			"api_key" => Config::get(Config::VAR_HM_API_KEY),
			"url" => $url
		];
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL,"https://api.hm.ru/key/url/shorten");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($d));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$server_output = json_decode(curl_exec($ch), 1);

		if($server_output["status"] == -1) {
			return $this->shortUrl($url);
		}
		curl_close ($ch);

		return $server_output["data"]["short_url"];
	}

	/**
	 * @param $d
	 * @return string
	 * @throws \ImagickException
	 */
	public static function qr($d)
	{
		$d = StringHelper::base64UrlDecode($d);
		$data = Yii::$app->security->decryptByKey($d, Config::get(Config::VAR_ENCRYPT_KEY));
		if(!$data) return "ERROR1";
		$data = json_decode($data, 1);

		$user = User::findOne($data["_userId"]);

		if(!$user) return "ERROR2";

		if(md5($user->id.$user->token.$user->created_at) != md5($data["_userId"].$data["_userToken"].$data["_userCreatedAt"])) {
			return "ERROR3";
		}

		$template = Template::findOne($data["_template"]);

		if ($template) {
			$template->createPdf(0, true, $data);
		} else {
			return "ERROR4";
		}

		return '';
	}

}
