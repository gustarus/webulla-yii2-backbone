<?php
/**
 * Created by Pavel Kondratenko.
 * Mail: gustarus@google.com
 * If you have any question you can contact with me.
 *
 * Date: 06.04.14
 * Time: 16:52
 */

namespace wbl\backbone\essence;

class View extends \wbl\backbone\base\Object {

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
}
 