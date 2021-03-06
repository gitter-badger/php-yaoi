<?php

namespace Yaoi\Database\Definition;

use Yaoi\BaseClass;

class Column extends BaseClass
{

    const AUTO_ID = 1;
    const INTEGER = 2;
    const UNSIGNED = 4;
    const FLOAT = 8;
    const STRING = 16;

    const SIZE_1B = 32;
    const SIZE_2B = 64;
    const SIZE_3B = 128;
    const SIZE_4B = 256;
    const SIZE_8B = 512;

    const TIMESTAMP = 1024;
    const NOT_NULL = 2048;

    const AUTO_TYPE = 4096;

    public static function castField($value, $columnType)
    {
        if (is_object($value)) {
            $value = (string)$value;
        }

        if ($columnType !== self::AUTO_TYPE) {
            switch ($columnType) {
                case self::FLOAT:
                    $value = (float)$value;
                    break;
                case self::INTEGER:
                    $value = (int)$value;
                    break;
                case self::STRING:
                    $value = (string)$value;
            }
        }

        return $value;
    }
}