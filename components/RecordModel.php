<?php
/**
 * Created by Pavel Kondratenko.
 * Mail: gustarus@google.com
 * If you have any question you can contact with me.
 *
 * Date: 06.04.14
 * Time: 16:35
 */

namespace wbl\backbone\components;

/**
 * Class RecordModel
 * @package wbl\backbone\components
 *
 * @property array record
 * @property array labels
 */
class RecordModel extends \wbl\backbone\essence\Model {

	/**
	 * Модель ActiveRecord на основе которой строится js модель
	 * @var \yii\db\ActiveRecord
	 */
	protected $record;


	/**
	 * Устанавливаем зависимую модель.
	 * @param \yii\db\ActiveRecord $record
	 */
	public function setRecord($record) {
		$this->record = $record;

		// получаем структуру backbone модели из записи
		if(isset($record->backbone)) {
			foreach($record->backbone as $key => $value) {
				$this->$key = $value;
			}
		}

		// добавляем класс на основе которого была создана модель backbone
		YII_DEBUG && $this->setVar('_bro_record', $record->className());
	}

	/**
	 * Возвращает зависимую модель.
	 * @return \yii\db\ActiveRecord
	 */
	public function getRecord() {
		return $this->record;
	}

	/**
	 * Добавляет лейблы для атрибутов.
	 * @param $labels
	 */
	public function setLabels($labels) {
		$this->setVar('labels', $labels);
	}

	/**
	 * Возвращает лейблы для атрибутов.
	 * @return mixed
	 */
	public function getLabels() {
		return $this->getVar('labels');
	}
}
 