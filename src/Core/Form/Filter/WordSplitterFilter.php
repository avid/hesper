<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Evgeniy N. Sokolov
 */
namespace Hesper\Core\Form\Filter;

/**
 * Class WordSplitterFilter
 * @package Hesper\Core\Form\Filter
 */
final class WordSplitterFilter implements Filtrator {

	private $maxWordLength = 25;
	private $delimer       = '&#x200B;';

	/**
	 * @return WordSplitterFilter
	 **/
	public static function create() {
		return new self;
	}

	/**
	 * @return WordSplitterFilter
	 **/
	public function setMaxWordLength($length) {
		$this->maxWordLength = $length;

		return $this;
	}

	public function getMaxWordLength() {
		return $this->maxWordLength;
	}

	/**
	 * @return WordSplitterFilter
	 **/
	public function setDelimer($delimer) {
		$this->delimer = $delimer;

		return $this;
	}

	public function getDelimer() {
		return $this->delimer;
	}

	public function apply($value) {
		return preg_replace('/([^\s]{' . $this->getMaxWordLength() . ',' . $this->getMaxWordLength() . '})([^\s])/u', '$1' . $this->getDelimer() . '$2', $value);
	}
}
