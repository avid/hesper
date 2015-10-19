<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Core\Form\Filter;

use Hesper\Core\Base\Assert;

/**
 * Class StripTagsFilter
 * @see     RegulatedPrimitive::addImportFilter()
 * @package Hesper\Core\Form\Filter
 */
final class StripTagsFilter implements Filtrator {

	private $exclude = null;

	/**
	 * @return StripTagsFilter
	 **/
	public static function create() {
		return new self;
	}

	/**
	 * @return StripTagsFilter
	 **/
	public function setAllowableTags($exclude) {
		if (null !== $exclude) {
			Assert::isString($exclude);
		}

		$this->exclude = $exclude;

		return $this;
	}

	public function apply($value) {
		return strip_tags($value, $this->exclude);
	}
}
