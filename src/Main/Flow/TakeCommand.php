<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Main\Flow;

use Hesper\Core\Base\Prototyped;
use Hesper\Core\Form\Form;

/**
 * Class TakeCommand
 * @package Hesper\Main\Flow
 */
abstract class TakeCommand implements EditorCommand {

	abstract protected function daoMethod();

	/**
	 * @return ModelAndView
	 **/
	public function run(Prototyped $subject, Form $form, HttpRequest $request) {
		$subject = $subject->dao()->{$this->daoMethod()}($subject);

		return ModelAndView::create()->setView(EditorController::COMMAND_SUCCEEDED)->setModel(Model::create()->set('id', $subject->getId()));
	}
}
