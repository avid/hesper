<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Main\Flow;

use Hesper\Core\Base\Identifiable;
use Hesper\Core\Base\Prototyped;
use Hesper\Core\Form\Form;

/**
 * Class DropCommand
 * @package Hesper\Main\Flow
 */
class DropCommand implements EditorCommand {

	/**
	 * @return DropCommand
	 **/
	public static function create() {
		return new self;
	}

	/**
	 * @return ModelAndView
	 **/
	public function run(Prototyped $subject, Form $form, HttpRequest $request) {
		if ($object = $form->getValue('id')) {

			if ($object instanceof Identifiable) {

				$object->dao()->drop($object);

				return ModelAndView::create()->setView(BaseEditor::COMMAND_SUCCEEDED);

			} else {
				// already deleted
				$form->markMissing('id');
			}
		}

		return ModelAndView::create();
	}
}
