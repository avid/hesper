<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\Base;

use Hesper\Core\Base\Prototyped;
use Hesper\Core\Form\Form;
use Hesper\Core\OSQL\InsertOrUpdateQuery;
use Hesper\Main\DAO\ProtoDAO;

/**
 * Class InnerMetaProperty
 * @package Hesper\Main\Base
 * @see     LightMetaProperty
 */
final class InnerMetaProperty extends LightMetaProperty {

	/**
	 * @return InnerMetaProperty
	 **/
	public static function create() {
		return new self;
	}

	public function isBuildable($array, $prefix = null) {
		return true;
	}

	public function fillMapping(array $mapping) {
		return array_merge($mapping, $this->getProto()
		                                  ->getMapping());
	}

	/**
	 * @return Form
	 **/
	public function fillForm(Form $form, $prefix = null) {
		foreach ($this->getProto()
		              ->getPropertyList() as $property) {
			$property->fillForm($form, $this->getName() . ':');
		}

		return $form;
	}

	public function fillQuery(InsertOrUpdateQuery $query, Prototyped $object, Prototyped $old = null) {
		$inner = $object->{$this->getGetter()}();
		$oldInner = $old ? $old->{$this->getGetter()}() : null;

		return $this->getProto()
		            ->fillQuery($query, $inner, //when old and objects have one value object
			            //  we'll update all valueObject fields:
			            $oldInner !== $inner ? $oldInner : null);
	}

	public function toValue(ProtoDAO $dao = null, $array, $prefix = null) {
		$proto = $this->getProto();

		return $proto->completeObject($proto->makeOnlyObject($this->getClassName(), $array, $prefix, $dao), $array, $prefix);
	}

	/**
	 * @return AbstractProtoClass
	 **/
	public function getProto() {
		return call_user_func([$this->getClassName(), 'proto']);
	}

	public function isFormless() {
		return true;
	}
}
