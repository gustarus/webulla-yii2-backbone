/**
 * Created by:  KODIX 19.12.13 22:10
 * Email:       support@kodix.ru
 * Web:         www.kodix.ru
 * Developer:   Pavel Kondratenko
 */

/**
 * Библиотека Rochen.
 * Расширяет стандартный функционал underscore и тесно связана с backbone.
 * Rochen является своеобразным дополнительным связующим звеном underscore и backbone.
 * Если rochen подключена после underscore, то она автоматически подмешивается в underscore.
 */
(function () {
	/**
	 * Инициализирует библиотеку.
	 * @param o
	 * @returns {*}
	 */
	var rochen = function (o) {
		if (o instanceof _)return o;
		if (!(this instanceof _))return new _(o);
		this._wrapped = o
	};

	// настраиваем библиотеку
	var context = this;
	var old = context.rochen;
	context.rochen = rochen;
	rochen.v = "1.0";


	/**
	 * Глубокий _.extend
	 * @param to
	 * @param from
	 * @returns {*}
	 */
	rochen.extend = function (to, from) {
		for (var i in from) {
			if (rochen.isCycled(from[i])) {
				to[i] = arguments.callee(to[i] || {}, from[i])
			} else {
				to[i] = from[i]
			}
		}

		return to
	};

	/**
	 * Глубокий _.defaults.
	 * @param to
	 * @param from
	 * @returns {*}
	 */
	rochen.defaults = function (to, from) {
		for (var i in from) {
			if (rochen.isCycled(to[i])) {
				to[i] = arguments.callee(to[i] || {}, from[i])
			} else if (typeof to[i] === "undefined") {
				to[i] = from[i]
			}
		}

		return to
	};

	/**
	 * Глубокий _.clone.
	 * @param from
	 * @returns {{}}
	 */
	rochen.clone = function (from) {
		var to = {};
		for (var i in from) {
			if (rochen.isCycled(from[i])) {
				to[i] = arguments.callee(from[i])
			} else if (rochen.isBackbone(from[i])) {
				to[i] = from[i]
			} else if (_.isFunction(from[i])) {
				to[i] = from[i]
			} else {
				to[i] = _.clone(from[i])
			}
		}

		return to
	};


	/**
	 * Является ли объект экземпляром backbone.
	 * @param model
	 * @returns {*|boolean|extend|Function|Backbone.Model.extend|Backbone.Collection.extend}
	 */
	rochen.isBackbone = function (model) {
		return model && typeof model == "function" && model.extend
	};

	/**
	 * Можно ли использовать объект в цикле.
	 * @param model
	 * @returns {*|boolean}
	 */
	rochen.isCycled = function (model) {
		return model && typeof model == "object" && !model.extend
	};

	/**
	 * Создает экземпляр объекта backbone.
	 * @param model
	 * @param options
	 * @returns {*}
	 */
	rochen.factory = function (model, options, debug) {
		switch (typeof model) {
			case"function":
				if (rochen.isBackbone(model)) { // backbone instance
					if (model.prototype.findWhere) {
						return new model([], options)
					} else if (model.prototype.previousAttributes) {
						return new model(null, options)
					} else if (model.prototype.render) {
						return new model(options)
					}
				}

				return new model;

			case"object":
				return model;

			default:
				rochen.error("Не могу создать модель.", true);
				return false
		}
	};


	/**
	 * Исключает конфликт библиотек.
	 * @returns {rochen}
	 */
	rochen.noConflict = function () {
		t.rochen = old;
		return this
	}
}).call(this);