<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\Net\Soap;

use Hesper\Core\Form\Form;
use Hesper\Main\EntityProto\Builder\FormToObjectConverter;
use Hesper\Main\EntityProto\Builder\ObjectToFormConverter;
use Hesper\Main\EntityProto\PrototypedEntity;

abstract class DTOClass implements PrototypedEntity {

	final public function makeObject(Form $form) {
		return FormToObjectConverter::create($this->entityProto())->make($form);
	}

	/**
	 * @return Form
	 **/
	final public function toForm() {
		return ObjectToFormConverter::create($this->entityProto())->make($this);
	}
}
