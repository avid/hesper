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
use Hesper\Core\Form\FormUtils;

/**
 * @ingroup Flow
 **/
abstract class MakeCommand extends TakeCommand {

	/**
	 * @return ModelAndView
	 **/
	public function run(Prototyped $subject, Form $form, HttpRequest $request) {
		$form->markGood('id');

		if (!$form->getErrors()) {
			FormUtils::form2object($form, $subject);

			return parent::run($subject, $form, $request);
		}

		return new ModelAndView();
	}
}
