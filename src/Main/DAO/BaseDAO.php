<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\DAO;

use Hesper\Core\Base\Identifiable;
use Hesper\Core\Logic\LogicalObject;
use Hesper\Core\OSQL\SelectQuery;
use Hesper\Main\DAO\Uncacher\UncacherBase;

/**
 * Interface BaseDAO
 * @package Hesper\Main\DAO
 */
interface BaseDAO {

	/// single object getters
	//@{
	public function getById($id);

	public function getByLogic(LogicalObject $logic);

	public function getByQuery(SelectQuery $query);

	public function getCustom(SelectQuery $query);
	//@}

	/// object's list getters
	//@{
	public function getListByIds(array $ids);

	public function getListByQuery(SelectQuery $query);

	public function getListByLogic(LogicalObject $logic);

	public function getPlainList();
	//@}

	/// custom list getters
	//@{
	public function getCustomList(SelectQuery $query);

	public function getCustomRowList(SelectQuery $query);
	//@}

	/// query result getter
	//@{
	public function getQueryResult(SelectQuery $query);
	//@}

	/// erasers
	//@{
	public function drop(Identifiable $object);

	public function dropById($id);

	public function dropByIds(array $ids);
	//@}

	/// uncachers
	//@{
	public function uncacheById($id);

	/**
	 * @return UncacherBase
	 */
	public function getUncacherById($id);

	public function uncacheByIds($ids);

	public function uncacheLists();
	//@}
}
