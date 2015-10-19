<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\UnifiedContainer;

use Hesper\Core\Base\Identifiable;
use Hesper\Main\DAO\GenericDAO;

/**
 * @ingroup Containers
 **/
abstract class ManyToManyLinked extends UnifiedContainer {

	abstract public function getHelperTable();

	public function getParentTableIdField() {
		return 'id';
	}

	public function __construct(Identifiable $parent, GenericDAO $dao, $lazy = true) {
		parent::__construct($parent, $dao, $lazy);

		$worker = $lazy ? ManyToManyLinkedLazy::class : ManyToManyLinkedFull::class;

		$this->worker = new $worker($this);
	}
}
