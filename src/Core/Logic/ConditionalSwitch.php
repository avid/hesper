<?php
/**
 * @project    Hesper Framework
 * @author     Михаил Кулаковский <m@klkvsk.ru>
 * @originally onPHP Framework
 */
namespace Hesper\Core\Logic;

use Hesper\Core\DB\Dialect;
use Hesper\Core\OSQL\DialectString;
use Hesper\Core\OSQL\JoinCapableQuery;
use Hesper\Main\DAO\ProtoDAO;

class ConditionalSwitch implements MappableObject {

    /**
     * @var array(array(expression, value), ..)
     */
    protected $cases = [];

    /** @var DialectString */
    protected $default = null;

    public function __construct() {
    }

    public function toDialectString(Dialect $dialect) {
        $sql = 'CASE ';
        foreach ($this->cases as $case) {
            /** @var $logic DialectString */
            $logic = $case[0];
            /** @var $value DialectString */
            $value = $case[1];

            $sql .= 'WHEN ' . $logic->toDialectString($dialect) . ' THEN ' . $value->toDialectString($dialect) . ' ';
        }

        if ($this->default != null) {
            $sql .= ' ELSE ' . $this->default->toDialectString($dialect) . ' ';
        }

        $sql .= 'END';

        return '(' . $sql . ')';
    }

    public static function create() {
        return new self;
    }

    public function addWhen(LogicalObject $logic, $value) {
        $this->cases[] = [$logic, $value];

        return $this;
    }

    public function addElse($value) {
        $this->default = $value;

        return $this;
    }

    /**
     * @param ProtoDAO         $dao
     * @param JoinCapableQuery $query
     *
     * @return ConditionalSwitch
     */
    public function toMapped(ProtoDAO $dao, JoinCapableQuery $query) {
        $mapped = new self;
        foreach ($this->cases as $case) {
            $mapped->addWhen($case[0]->toMapped($dao, $query), $dao->guessAtom($case[1], $query));
        }
        if ($this->default !== null) {
            $mapped->addElse($dao->guessAtom($this->default, $query));
        }

        return $mapped;
    }


}