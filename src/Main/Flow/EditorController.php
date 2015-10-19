<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich
 */
namespace Hesper\Main\Flow;

use Hesper\Core\Base\Prototyped;

/**
 * Class EditorController
 * @package Hesper\Main\Flow
 */
abstract class EditorController extends BaseEditor {

	public function __construct(Prototyped $subject) {
		$this->commandMap['import'] = new ImportCommand();
		$this->commandMap['drop'] = new DropCommand();
		$this->commandMap['save'] = new SaveCommand();
		$this->commandMap['edit'] = new EditCommand();
		$this->commandMap['add'] = new AddCommand();

		parent::__construct($subject);
	}

	/**
	 * @return ModelAndView
	 **/
	public function handleRequest(HttpRequest $request) {
		$this->map->import($request);

		$form = $this->getForm();

		if (!$command = $form->getValue('action')) {
			$command = $form->get('action')->getDefault();
		}

		if ($command) {
			$mav = $this->commandMap[$command]->run($this->subject, $form, $request);
		} else {
			$mav = ModelAndView::create();
		}

		return $this->postHandleRequest($mav, $request);
	}
}
