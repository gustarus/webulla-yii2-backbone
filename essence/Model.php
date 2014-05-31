<?php
/**
 * Created by Pavel Kondratenko.
 * Mail: gustarus@google.com
 * If you have any question you can contact with me.
 *
 * Date: 06.04.14
 * Time: 16:51
 */

namespace wbl\backbone\essence;

/**
 * Class Model
 * @package wbl\backbone\essence
 *
 * @property array defaults
 */
class Model extends \wbl\backbone\base\Object {

	/**
	 * @inheritdoc
	 */
	public function getJsName() {
		return $this->name;
	}

	/**
	 * @inheritdoc
	 */
	public function getJsExtends() {
		return $this->prototype ? $this->prototype->jsName : 'Backbone.Model';
	}


	/**
	 * Добавляет атрибуты по умолчанию.
	 * @param $defaults
	 */
	public function setDefaults($defaults) {
		$this->setVar('defaults', $defaults);
	}

	/**
	 * Возвращает атрибуты по умолчанию.
	 * @return mixed
	 */
	public function getDefaults() {
		return $this->getVar('defaults');
	}
}
 