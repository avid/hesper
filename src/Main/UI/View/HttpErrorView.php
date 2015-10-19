<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Main\UI\View;

use Hesper\Main\Flow\Model;
use Hesper\Main\Net\Http\HttpStatus;

/**
 * Class HttpErrorView
 * @package Hesper\Main\UI\View
 */
class HttpErrorView implements View {

	protected $status = null;

	protected $prefix  = null;
	protected $postfix = null;

	public function __construct(HttpStatus $status, $prefix, $postfix) {
		$this->status = $status;

		$this->prefix = $prefix;
		$this->postfix = $postfix;
	}

	/* void */
	public function render(Model $model = null) {
		header($this->status->toString());
		include $this->prefix . $this->status->getId() . $this->postfix;
	}
}
