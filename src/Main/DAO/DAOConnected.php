<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\DAO;

use Hesper\Core\Base\Identifiable;

/**
 * Helper for identifying object's DAO.
 * @package Hesper\Main\DAO
 */
interface DAOConnected extends Identifiable {

	/**
	 * @return GenericDAO
	 */
	public static function dao();
}
