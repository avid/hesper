<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Georgiy T. Kutsurua
 */
namespace Hesper\Main\UI\View;

use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Main\Flow\Model;

/**
 * Class JsonXssView
 * @package Hesper\Main\UI\View
 */
class JsonXssView extends JsonPView {

	/**
	 * Javascript valid function name pattern
	 */
	const CALLBACK_PATTERN = '/^[\$A-Z_][0-9A-Z_\$\.]*$/i';

	/**
	 * Default prefix
	 * @var string
	 */
	protected $prefix = 'window.';

	/**
	 * Default callback
	 * @var string
	 */
	protected $callback = 'name';

	/**
	 * @static
	 * @return JsonXssView
	 */
	public static function create() {
		return new self();
	}

	/**
	 * @param $value
	 *
	 * @return JsonXssView
	 * @throws WrongArgumentException
	 */
	public function setPrefix($value) {
		if (!preg_match(static::CALLBACK_PATTERN, $value)) {
			throw new WrongArgumentException('invalid prefix name, you should set valid javascript function name! gived "' . $value . '"');
		}

		$this->prefix = $value;

		return $this;
	}

	/**
	 * @param Model $model
	 *
	 * @return string
	 */
	public function toString(Model $model = null) {
		/*
		 * Escaping warning datas
		 */
		$this->setHexAmp(true);
		$this->setHexApos(true);
		$this->setHexQuot(true);
		$this->setHexTag(true);

		$json = JsonView::toString($model);

		$json = str_ireplace(['u0022', 'u0027'], ['\u0022', '\u0027'], $json);

		$result = '<script type="text/javascript">' . "\n";
		$result .= "\t" . $this->prefix . $this->callback . '=\'' . $json . '\';' . "\n";
		$result .= '</script>' . "\n";

		return $result;
	}

}
