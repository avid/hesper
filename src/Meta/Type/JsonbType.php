<?php

namespace Hesper\Meta\Type;

use \Hesper\Core\Exception\UnimplementedFeatureException;
use \Hesper\Meta\Entity\MetaClass;
use \Hesper\Meta\Entity\MetaClassProperty;
use \Hesper\Core\Base\Assert;

/**
 * @ingroup Types
 * @see http://www.postgresql.org/docs/9.4/static/datatype-json.html
 * @author Anton Gurov <anton.gurov@gmail.com>
 * @date 2015.09.12
 */
class JsonbType extends ArrayType {

    public function getPrimitiveName()
    {
        return 'jsonb';
    }

    public function toColumnType()
    {
        return 'DataType::create(DataType::JSONB)';
    }

    //Нельзя измерить JSON, у него нет длины в строгом смысле
    public function isMeasurable() {
        return false;
    }

}