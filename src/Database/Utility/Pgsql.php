<?php

namespace Yaoi\Database\Utility;

use Yaoi\Database\Utility;
use Yaoi\Database\Definition\Column;
use Yaoi\Database\Definition\Table;

class Pgsql extends Utility
{
    /**
     * @param $tableName
     * @return Table
     */
    public function getTableDefinition($tableName)
    {
        $def = new Table();

        $res = $this->database->query("select c.column_name, c.is_nullable, c.data_type, c.column_default, tc.constraint_type
from INFORMATION_SCHEMA.COLUMNS AS c
  LEFT JOIN INFORMATION_SCHEMA.constraint_column_usage AS ccu ON c.column_name = ccu.column_name AND c.table_name = ccu.table_name
  LEFT JOIN INFORMATION_SCHEMA.table_constraints AS tc ON ccu.constraint_name = tc.constraint_name
where c.table_name = '$tableName';
");
        while ($r = $res->fetchRow()) {
            $field = $r['column_name'];
            $type = $r['data_type'];
            $phpType = Column::AUTO_TYPE;

            switch (true) {
                case 'integer' === substr($type, 0, 7):
                case 'smallint' === substr($type, 0, 8):
                case 'bigint' === substr($type, 0, 6):
                    $phpType = Column::INTEGER;
                    break;

                case 'numeric' === substr($type, 0, 7):
                case 'double' === substr($type, 0, 6):
                case 'real' === substr($type, 0, 4):
                    $phpType = Column::FLOAT;
                    break;

                case 'character' === substr($type, 0, 9):
                case 'text' === $type:
                    $phpType = Column::STRING;
                    break;

                case 'time' === $type:
                case 'time ' === substr($type, 0, 5):
                    $phpType = Column::STRING;
                    break;

                case 'timestamp' === substr($type, 0, 9):
                case 'date' === substr($type, 0, 4):
                    $phpType = Column::TIMESTAMP;
                    break;

            }

            $def->defaults[$field] = $r['column_default'];
            if ('nextval' === substr($r['column_default'], 0, 7)) {
                $def->autoIncrement = $field;
            }
            $def->columns[$field] = $phpType;
            $def->notNull[$field] = $r['is_nullable'] === 'NO';
            if ($r['constraint_type'] === 'PRIMARY KEY') {
                $def->primaryKey [$field] = $field;
            }


        }
        return $def;
    }

}