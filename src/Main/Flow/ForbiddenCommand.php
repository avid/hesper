<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\Flow;

use Hesper\Core\Base\Prototyped;
use Hesper\Core\Form\Form;

/**
 * Class ForbiddenCommand
 * @package Hesper\Main\Flow
 */
final class ForbiddenCommand implements EditorCommand {

	/**
	 * @return ForbiddenCommand
	 **/
	public static function create() {
		return new self;
	}

	/**
	 * @return ModelAndView
	 **/
	public function run(Prototyped $subject, Form $form, HttpRequest $request) {
		return ModelAndView::create()->setView(EditorController::COMMAND_FAILED);
	}
}
