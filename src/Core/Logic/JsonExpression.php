<?php
namespace Hesper\Core\Logic;

use Hesper\Core\Base\StaticFactory;
use Hesper\Core\DB\Dialect;
use Hesper\Core\OSQL\Castable;
use Hesper\Main\Base\Hstore;

/**
 * Class JsonExpression
 * @see     https://www.postgresql.org/docs/9.5/static/functions-json.html
 * @package Hesper\Core\Logic
 * @author  anton.gurov@gmail.com
 */
class JsonExpression extends Castable {

    //json & jsonb type comaptible operators
    const GET_VALUE             = '->';
    const GET_VALUE_AS_TEXT     = '->>';
    const GET_PATH              = '#>';
    const GET_PATH_AS_TEXT      = '#>>';

    //jsonb exclusive operators

    const CONTAINS              = '@>';
    const CONTAINED_BY          = '<@';

    //      ? 	text 	Does the string exist as a top-level key within the JSON value? 	'{"a":1, "b":2}'::jsonb ? 'b'
    const HAS                = '?';
    // ?| 	text[] 	Do any of these array strings exist as top-level keys? 	'{"a":1, "b":2, "c":3}'::jsonb ?| array['b', 'c']
    const HAS_ANY            = '?|';
    // ?& 	text[] 	Do all of these array strings exist as top-level keys? 	'["a", "b"]'::jsonb ?& array['a', 'b']
    const HAS_ALL            = '?&';

    /** @var $value BinaryExpression */
    public $value;


    // || 	jsonb 	Concatenate two jsonb values into a new jsonb value 	'["a", "b"]'::jsonb || '["c", "d"]'::jsonb
    const CONCAT                = '||';
    //- 	text 	Delete key/value pair or string element from left operand. Key/value pairs are matched based on their key value. 	'{"a": "b"}'::jsonb - 'a'
    const DELETE                = '-';
    // #- 	text[] 	Delete the field or element with specified path (for JSON arrays, negative integers count from the end) 	'["a", {"b":1}]'::jsonb #- '{1,b}'
    const DELETE_PATH           = '#-';

    public static function create() {
        return new self;
    }

    public function getValue($field, $key) {
        $this->value = new BinaryExpression($field, $key, self::GET_VALUE);
        return $this;
    }

    public function getValueString($field, $key) {
        $this->value = new BinaryExpression($field, $key, self::GET_VALUE_AS_TEXT);
        return $this;
    }

    public function getValueByPath($field, $key) {
        $this->value = new BinaryExpression($field, $key, self::GET_PATH);
        return $this;
    }

    public function getValueStringByPath($field, $key) {
        $this->value = new BinaryExpression($field, $key, self::GET_PATH_AS_TEXT);
        return $this;
    }

    public function contains($field, $key) {
        $this->value = new BinaryExpression($field, $key, self::CONTAINS);
        return $this;
    }

    public function containedBy($field, $key) {
        $this->value = new BinaryExpression($field, $key, self::CONTAINED_BY);
        return $this;
    }

    public function has($field, $key) {
        $this->value = new BinaryExpression($field, $key, self::HAS);
        return $this;
    }

    public function hasAny($field, $key) {
        $this->value = new BinaryExpression($field, $key, self::HAS_ANY);
        return $this;
    }

    public function hasAll($field, $key) {
        $this->value = new BinaryExpression($field, $key, self::HAS_ALL);
        return $this;
    }

    public function toDialectString(Dialect $dialect) {
        return $dialect::toCasted($this->value->toDialectString($dialect), $this->cast);

    }
}