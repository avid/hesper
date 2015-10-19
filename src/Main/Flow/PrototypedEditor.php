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
use Hesper\Core\Form\FormUtils;
use Hesper\Core\Form\MappedForm;
use Hesper\Main\Base\RequestType;

/**
 * Class PrototypedEditor
 * @package Hesper\Main\Flow
 */
abstract class PrototypedEditor extends MethodMappedController {

	const COMMAND_SUCCEEDED = 'success';
	const COMMAND_FAILED    = 'error';

	protected $subject = null;
	protected $map     = null;

	public function __construct(Prototyped $subject) {
		$this->subject = $subject;
		$this->map = MappedForm::create($this->subject->proto()->makeForm())->addSource('id', RequestType::get())->setDefaultType(RequestType::post());

		$this->setMethodMapping('drop', 'doDrop')->setMethodMapping('take', 'doTake')->setMethodMapping('save', 'doSave')->setMethodMapping('edit', 'doEdit')->setMethodMapping('add', 'doAdd');

		$this->setDefaultAction('edit');
	}

	/**
	 * @return ModelAndView
	 **/
	public function doDrop(HttpRequest $request) {
		$this->map->import($request);
		$form = $this->getForm();

		if ($object = $form->getValue('id')) {
			if ($object instanceof Identifiable) {

				$this->dropObject($request, $form, $object);

				return ModelAndView::create()->setModel(Model::create()->set('editorResult', self::COMMAND_SUCCEEDED));

			} else {

				// already deleted
				$form->markMissing('id');

				return ModelAndView::create()->setModel(Model::create()->set('editorResult', self::COMMAND_FAILED)->set('form', $form));
			}
		} else {
			return ModelAndView::create()->setModel(Model::create()->set('editorResult', self::COMMAND_FAILED)->set('form', $form));
		}
	}

	protected function dropObject(HttpRequest $request, Form $form, Identifiable $object) {
		$object->dao()->drop($object);
	}

	/**
	 * @return ModelAndView
	 **/
	public function doTake(HttpRequest $request) {
		$this->map->import($request);
		$form = $this->getForm();

		if (!$form->getRawValue('id')) {

			$isAdd = true;
			$form->markGood('id');
			$object = clone $this->subject;

		} else {

			$object = $form->getValue('id');
			$isAdd = false;
		}

		if (!$form->getErrors()) {
			$object = $isAdd ? $this->addObject($request, $form, $object) : $this->saveObject($request, $form, $object);

			$editorResult = $form->getErrors() ? self::COMMAND_FAILED : self::COMMAND_SUCCEEDED;

			return ModelAndView::create()->setModel(Model::create()->set('id', $object->getId())->set('subject', $object)->set('form', $form)->set('editorResult', $editorResult));
		} else {
			$model = Model::create()->set('form', $form)->set('editorResult', self::COMMAND_FAILED);

			if ($object) {
				$model->set('subject', $object);
			}

			return ModelAndView::create()->setModel($model);
		}
	}

	/**
	 * @return ModelAndView
	 **/
	public function doSave(HttpRequest $request) {
		$this->map->import($request);
		$form = $this->getForm();

		$object = $form->getValue('id');

		if (!$form->getErrors()) {

			$object = $this->saveObject($request, $form, $object);

			$editorResult = $form->getErrors() ? self::COMMAND_FAILED : self::COMMAND_SUCCEEDED;

			return ModelAndView::create()->setModel(Model::create()->set('id', $object->getId())->set('subject', $object)->set('form', $form)->set('editorResult', $editorResult));
		} else {
			$model = Model::create()->set('form', $form)->set('editorResult', self::COMMAND_FAILED);

			if ($object) {
				$model->set('subject', $object);
			}

			return ModelAndView::create()->setModel($model);
		}
	}

	protected function saveObject(HttpRequest $request, Form $form, Identifiable $object) {
		FormUtils::form2object($form, $object, false);

		return $object->dao()->save($object);
	}

	/**
	 * @return ModelAndView
	 **/
	public function doEdit(HttpRequest $request) {
		$this->map->import($request);
		$form = $this->getForm();

		if ($form->getValue('id')) {
			$object = $form->getValue('id');
		} else {
			$object = clone $this->subject;
		}

		FormUtils::object2form($object, $form);

		$form->dropAllErrors();

		return ModelAndView::create()->setModel(Model::create()->set('subject', $object)->set('form', $form));
	}

	/**
	 * @return ModelAndView
	 **/
	public function doAdd(HttpRequest $request) {
		$this->map->import($request);
		$form = $this->getForm();

		$form->markGood('id');
		$object = clone $this->subject;

		if (!$form->getErrors()) {

			$object = $this->addObject($request, $form, $object);

			$editorResult = $form->getErrors() ? self::COMMAND_FAILED : self::COMMAND_SUCCEEDED;

			return ModelAndView::create()->setModel(Model::create()->set('id', $object->getId())->set('subject', $object)->set('form', $form)->set('editorResult', $editorResult));
		} else {
			return ModelAndView::create()->setModel(Model::create()->set('form', $form)->set('subject', $object)->set('editorResult', self::COMMAND_FAILED));
		}
	}

	/**
	 * @return Form
	 **/
	public function getForm() {
		return $this->map->getForm();
	}

	protected function addObject(HttpRequest $request, Form $form, Identifiable $object) {
		FormUtils::form2object($form, $object);

		return $object->dao()->add($object);
	}
}
