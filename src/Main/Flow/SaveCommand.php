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
use Hesper\Core\Form\FormUtils;
use Hesper\Main\Util\ClassUtils;

/**
 * Class SaveCommand
 * @package Hesper\Main\Flow
 */
class SaveCommand extends TakeCommand {

	/**
	 * @return SaveCommand
	 **/
	public static function create() {
		return new self;
	}

	/**
	 * @return ModelAndView
	 **/
	public function run(Prototyped $subject, Form $form, HttpRequest $request) {
		if (!$form->getErrors()) {
			ClassUtils::copyProperties($form->getValue('id'), $subject);

			FormUtils::form2object($form, $subject, false);

			return parent::run($subject, $form, $request);
		}

		return new ModelAndView();
	}

	protected function daoMethod() {
		return 'save';
	}
}
