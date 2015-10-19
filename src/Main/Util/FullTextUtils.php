<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\Util;

use Hesper\Core\Base\Assert;
use Hesper\Core\Base\StaticFactory;
use Hesper\Core\Exception\ObjectNotFoundException;
use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Core\Logic\Expression;
use Hesper\Core\OSQL\DBField;
use Hesper\Core\OSQL\SelectQuery;
use Hesper\Main\Criteria\Criteria;
use Hesper\Main\DAO\FullTextDAO;

/**
 * Full-text utilities.
 * @package Hesper\Main\Util
 */
final class FullTextUtils extends StaticFactory {

	public static function lookup(FullTextDAO $dao, Criteria $criteria, $string) {
		return $dao->getByQuery(self::makeFullTextQuery($dao, $criteria, $string)->limit(1));
	}

	public static function lookupList(FullTextDAO $dao, Criteria $criteria, $string) {
		return $dao->getListByQuery(self::makeFullTextQuery($dao, $criteria, $string));
	}

	/**
	 * @throws WrongArgumentException
	 * @return SelectQuery
	 **/
	public static function makeFullTextQuery(FullTextDAO $dao, Criteria $criteria, $string) {
		Assert::isString($string, 'only strings accepted today');

		$array = self::prepareSearchString($string);

		if (!$array) {
			throw new ObjectNotFoundException();
		}

		if (!($field = $dao->getIndexField()) instanceof DBField) {
			$field = new DBField($dao->getIndexField(), $dao->getTable());
		}

		return $criteria->toSelectQuery()->andWhere(Expression::fullTextOr($field, $array))->prependOrderBy(Expression::fullTextRankAnd($field, $array))->desc();
	}

	public static function prepareSearchString($string) {
		$array = preg_split('/[\s\pP]+/u', $string);

		$out = [];

		for ($i = 0, $size = count($array); $i < $size; ++$i) {
			if (!empty($array[$i]) && ($element = preg_replace('/[^\pL\d\-\+\.\/]/u', null, $array[$i]))) {
				$out[] = $element;
			}
		}

		return $out;
	}
}
