<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Denis M. Gabaidulin
 */
namespace Hesper\Core\Form\Filter;

use Hesper\Core\Base\Assert;

/**
 * Class CropFilter
 * @see     RegulatedPrimitive::addImportFilter()
 * @package Hesper\Core\Form\Filter
 */
final class CropFilter implements Filtrator {

	private $start  = 0;
	private $length = 0;

	/**
	 * @return CropFilter
	 **/
	public static function create() {
		return new self;
	}

	/**
	 * @return CropFilter
	 **/
	public function setStart($start) {
		Assert::isPositiveInteger($start);

		$this->start = $start;

		return $this;
	}

	/**
	 * @return CropFilter
	 **/
	public function setLength($length) {
		Assert::isPositiveInteger($length);

		$this->length = $length;

		return $this;
	}

	public function apply($value) {
		return $this->length ? mb_strcut($value, $this->start, $this->length) : mb_strcut($value, $this->start);
	}
}
