<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Anton E. Lebedevich, Konstantin V. Arkhipov
 */
namespace Hesper\Main\Flow;

use Hesper\Core\Base\Prototyped;
use Hesper\Core\Form\Form;

/**
 * Class MonolithicController
 * @package Hesper\Main\Flow
 */
abstract class MonolithicController extends BaseEditor {

	public function __construct(Prototyped $subject) {
		$this->commandMap['import'] = 'doImport';
		$this->commandMap['drop'] = 'doDrop';
		$this->commandMap['save'] = 'doSave';
		$this->commandMap['edit'] = 'doEdit';
		$this->commandMap['add'] = 'doAdd';

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
			$mav = $this->{$this->commandMap[$command]}($this->subject, $form, $request);
		} else {
			$mav = ModelAndView::create();
		}

		return $this->postHandleRequest($mav, $request);
	}

	/**
	 * @return ModelAndView
	 **/
	public function doImport(Prototyped $subject, Form $form, HttpRequest $request) {
		return ImportCommand::create()->run($subject, $form, $request);
	}

	/**
	 * @return ModelAndView
	 **/
	public function doDrop(Prototyped $subject, Form $form, HttpRequest $request) {
		return DropCommand::create()->run($subject, $form, $request);
	}

	/**
	 * @return ModelAndView
	 **/
	public function doSave(Prototyped $subject, Form $form, HttpRequest $request) {
		return SaveCommand::create()->run($subject, $form, $request);
	}

	/**
	 * @return ModelAndView
	 **/
	public function doEdit(Prototyped $subject, Form $form, HttpRequest $request) {
		return EditCommand::create()->run($subject, $form, $request);
	}

	/**
	 * @return ModelAndView
	 **/
	public function doAdd(Prototyped $subject, Form $form, HttpRequest $request) {
		return AddCommand::create()->run($subject, $form, $request);
	}
}
