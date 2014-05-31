<?php
/**
 * Created by PhpStorm.
 * User: supreme
 * Date: 16.04.14
 * Time: 0:59
 */

namespace wbl\backbone\assets;

use yii\web\AssetBundle;
use yii\web\View;

class Asset extends AssetBundle {

	/**
	 * @inheritdoc
	 */
	public $sourcePath = '@wbl/backbone/public';

	/**
	 * @inheritdoc
	 */
	public $js = [
		'js/tools/underscore.js',
		'js/tools/rochen.js',
		'js/tools/backbone.js',
	];

	/**
	 * @inheritdoc
	 */
	public $jsOptions = [
		'position' => View::POS_HEAD,
	];

	/**
	 * @inheritdoc
	 */
	public $depends = [
		'app\assets\Asset',
	];
}