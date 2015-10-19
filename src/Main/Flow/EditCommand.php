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

/**
 * @ingroup Flow
 **/
class EditCommand implements EditorCommand {

	/**
	 * @return EditCommand
	 **/
	public static function create() {
		return new self;
	}

	/**
	 * @return ModelAndView
	 **/
	public function run(Prototyped $subject, Form $form, HttpRequest $request) {
		if ($object = $form->getValue('id')) {
			FormUtils::object2form($object, $form);
		}

		return ModelAndView::create();
	}
}
