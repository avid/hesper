<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Georgiy T. Kutsurua
 */
namespace Hesper\Main\UI\View;

use Hesper\Core\Base\Assert;
use Hesper\Core\Base\Stringable;
use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Main\Flow\Model;

/**
 * Class JsonPView
 * @package Hesper\Main\UI\View
 */
class JsonPView extends JsonView {

	/**
	 * Javascript valid function name pattern
	 */
	const CALLBACK_PATTERN = '/^[\$A-Z_][0-9A-Z_\$]*$/i';

	/**
	 * @static
	 * @return JsonPView
	 */
	public static function create() {
		return new self();
	}

	/**
	 * Callback function name
	 * @see http://en.wikipedia.org/wiki/JSONP
	 * @var string
	 */
	protected $callback = null;

	/**
	 * @param mixed $callback
	 * @return JsonPView
	 */
	public function setCallback($callback) {
		$realCallbackName = null;

		if (is_scalar($callback)) {
			$realCallbackName = $callback;
		} elseif ($callback instanceof Stringable) {
			$realCallbackName = $callback->toString();
		} else {
			throw new WrongArgumentException('undefined type of callback, gived "' . gettype($callback) . '"');
		}

		if (!preg_match(static::CALLBACK_PATTERN, $realCallbackName)) {
			throw new WrongArgumentException('invalid function name, you should set valid javascript function name! gived "' . $realCallbackName . '"');
		}

		$this->callback = $realCallbackName;

		return $this;
	}

	/**
	 * @param Model $model
	 *
	 * @return string
	 */
	public function toString(Model $model = null) {
		Assert::isNotEmpty($this->callback, 'callback can not be empty!');

		$json = parent::toString($model);

		return $this->callback . '(' . $json . ');';
	}

}
