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
abstract class OneToManyLinked extends UnifiedContainer
{
	public function __construct(
		Identifiable $parent, GenericDAO $dao, $lazy = true
	)
	{
		parent::__construct($parent, $dao, $lazy);

		$worker =
			$lazy
				? OneToManyLinkedLazy::class
				: OneToManyLinkedFull::class;

		$this->worker = new $worker($this);
	}

	public function getChildIdField()
	{
		return 'id';
	}

	public function isUnlinkable()
	{
		return false;
	}

	public function getHelperTable()
	{
		return $this->dao->getTable();
	}
}
