<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\EntityProto\Builder;

use Hesper\Core\Base\Assert;
use Hesper\Core\Form\Form;
use Hesper\Core\Form\Primitive\PrimitiveForm;
use Hesper\Main\EntityProto\PrototypedBuilder;

/**
 * Class FormBuilder
 * @package Hesper\Main\EntityProto\Builder
 */
abstract class FormBuilder extends PrototypedBuilder {

	/**
	 * @return Form
	 **/
	protected function createEmpty() {
		return Form::create();
	}

	/**
	 * @return Form
	 **/
	public function fillOwn($object, &$result) {
		Assert::isInstance($result, Form::class);

		foreach ($this->getFormMapping() as $primitive) {
			if ($primitive instanceof PrimitiveForm && $result->exists($primitive->getName()) && $primitive->isComposite()) {

				Assert::isEqual($primitive->getProto(), $result->get($primitive->getName())->getProto());

				continue;
			}

			$result->add($primitive);
		}

		$result = parent::fillOwn($object, $result);

		$result->setProto($this->proto);

		return $result;
	}
}
