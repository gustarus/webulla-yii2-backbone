<?php
/**
 * Created by PhpStorm.
 * User: supreme
 * Date: 08.04.14
 * Time: 0:35
 */

namespace wbl\backbone\components;

/**
 * Class SearchModel
 * @package wbl\backbone\components
 *
 * @property \yii\db\ActiveRecord $record
 * @property string $model
 */
class SearchModel extends \wbl\backbone\essence\Model {

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

		// добавляем метод поиска моделей
		$this->setMethod('search', 'params, options', '
			options || (options = {});
			var that = this;

			var success = options.success;
			Backbone.sync(\'read\', that, _.extend(options, {
				data: {ajax: true, ' . json_encode($record->formName()) . ': params},
				success: function(result) {
					success && success.apply(this, arguments);

					var models = [];
					_.each(result, function(item) {
						models.push(new that.model(item));
					});

					that.trigger(\'search\', models);
				}
			}));
		');

		// идентификатор модели
		YII_DEBUG && $this->setVar('_bro_record', $record->className());
	}

	/**
	 * Возвращает зависимую модель.
	 * @return \yii\db\ActiveRecord
	 */
	public function getRecord() {
		return $this->record;
	}
}