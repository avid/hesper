<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\DAO;

/**
 * Support interface for use with FullTextUtils.
 * @package Hesper\Main\DAO
 */
interface FullTextDAO extends BaseDAO {

	// index' field name
	public function getIndexField();
}
