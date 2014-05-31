<?php
/**
 * Created by Pavel Kondratenko.
 * Mail: gustarus@google.com
 * If you have any question you can contact with me.
 *
 * Date: 06.04.14
 * Time: 16:04
 */

namespace wbl\backbone\base;

use Yii;
use wbl\backbone\assets\Asset;

/**
 * Class Model
 * @package wbl\backbone\components
 *
 * @property string $jsName
 * @property string $jsExtends
 *
 * @property array $properties
 * @property array $vars
 * @property array $methods
 */
abstract class Object extends \yii\base\Object {

	/**
	 * Прототип модели для использования в extends.
	 * @var Object
	 */
	public $prototype;

	/**
	 * Название модели.
	 * @var
	 */
	public $name;


	/**
	 * Переменные js модели.
	 * К каждому элементу применяется {@function json_encode}.
	 * @var array
	 */
	protected $vars = [];

	/**
	 * Свосйства js модели.
	 * @var array
	 */
	protected $properties = [];

	/**
	 * Методы js модели.
	 * Каждый элемент имеет вид: $method => [$arguments, $function].
	 * @var array
	 */
	protected $methods = [];


	/**
	 * @inheritdoc
	 */
	public function init() {
		parent::init();

		// добавляем класс из которого был создан объект backbone
		YII_DEBUG && $this->setVar('_bro_factory', $this->className());
	}

	/**
	 * Компилируем модель.
	 * @return string
	 */
	public function __toString() {
		return $this->compile();
	}


	/**
	 * Возвращает неймспейс модели для использования в js.
	 * @return string
	 */
	abstract function getJsName();

	/**
	 * Возвращает неймспейс extends модели для использования в js.
	 * @return string
	 */
	abstract function getJsExtends();


	/**
	 * Добавляет переменную.
	 * @param string $name
	 * @param mixed $value
	 */
	public function setVar($name, $value) {
		$this->vars[$name] = $value;
	}

	/**
	 * Возвращает переменную.
	 * @param string $name
	 * @return mixed
	 */
	public function getVar($name) {
		return $this->vars[$name];
	}

	/**
	 * Добавляем переменные.
	 * @param array $values
	 */
	public function setVars($values) {
		foreach($values as $name => $value) {
			$this->setVar($name, $value);
		}
	}

	/**
	 * Возвращает список переменных.
	 * @return mixed
	 */
	public function getVars() {
		return $this->vars;
	}

	/**
	 * Добавляет свойство.
	 * @param string $name
	 * @param mixed $value
	 */
	public function setProperty($name, $value) {
		$this->properties[$name] = $value;
	}

	/**
	 * Возвращает свойство.
	 * @param string $name
	 * @return mixed
	 */
	public function getProperty($name) {
		return $this->properties[$name];
	}

	/**
	 * Добавляет свойства.
	 * @param array $values
	 */
	public function setProperties($values) {
		foreach($values as $name => $value) {
			$this->setProperty($name, $value);
		}
	}

	/**
	 * Возвращает список свойств.
	 * @return mixed
	 */
	public function getProperties() {
		return $this->properties;
	}

	/**
	 * Добавляет описание метода.
	 * @param string $name
	 * @param string $arguments
	 * @param string $function
	 */
	public function setMethod($name, $arguments, $function) {
		$this->methods[$name] = [$arguments, $function];
	}

	/**
	 * Возвращает описание метода.
	 * @param string $name
	 * @return mixed
	 */
	public function getMethod($name) {
		return $this->methods[$name];
	}

	/**
	 * Добавляет описание методов.
	 * @param array $values
	 */
	public function setMethods($values) {
		foreach($values as $name => $params) {
			$this->setMethod($name, $params[0], $params[1]);
		}
	}

	/**
	 * Возвращает описание методов.
	 * @return mixed
	 */
	public function getMethods() {
		return $this->methods;
	}


	/**
	 * Компиляция модели.
	 * @return string
	 */
	public function compile() {
		return $this->compileBefore() . $this->compileDefinition() . $this->compileAfter();
	}

	/**
	 * Компилирует текст перед определением модели.
	 * @return string
	 */
	public function compileBefore() {
		return '';
	}

	/**
	 * Компилирует определение модели.
	 * @return string
	 */
	public function compileDefinition() {
		return 'malkoln.setProperty(window, ' . json_encode($this->jsName) . ', ' . $this->jsExtends . '.extend({' . $this->compileScheme() . '}));';
	}

	/**
	 * Компилирует текст после определения модели.
	 * @return string
	 */
	public function compileAfter() {
		return '';
	}

	/**
	 * Компилирует схему модели.
	 * @return string
	 */
	public function compileScheme() {
		// компилируем свойства
		$properties = [];
		foreach($this->properties as $key => $value) {
			$properties[] = $key . ': ' . $value;
		}

		// компилируем переменные
		$vars = [];
		foreach($this->vars as $key => $value) {
			$vars[] = $key . ': ' . json_encode($value);
		}

		// компилируем методы
		$methods = [];
		foreach($this->methods as $name => $params) {
			$methods[] = $name . ': function(' . (is_array($params[0]) ? implode(', ', $params[0]) : $params[0]) . ') '
				. '{' . str_replace("\n", '', $params[1]) . '}';
		}

		// компилируем схему модели
		$scheme = implode(',', array_merge($properties, $vars, $methods));

		return $scheme;
	}


	/**
	 * Выполняет регистрацию js скрипта модели.
	 */
	public function register() {
		$view = Yii::$app->controller->view;

		// регистрируем asset
		Asset::register($view);

		// регистрируем модель
		$view->registerJs($this->compile());
	}
}
 