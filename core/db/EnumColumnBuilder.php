<?php

namespace app\core\db;

/**
 * Implements a method to be used in migrations in order to create enum columns.
 */
trait EnumColumnBuilder
{
    /**
     * Generates SQL code for an enum column.
     * 
     * Example:
     * 
     * ```php
     * class m220731_120614_create_user extends Migration
     * {
     *     use EnumColumnBuilder;
     * 
     *     public function safeUp()
     *     {
     *         $this->createTable(User::tableName(), [
     *             // ...
     *             'status' => $this->enum(['active', 'deleted']),
     *             // ...
     *         ]);
     *     }
     * }
     * ```
     * 
     * @param string[] $values Enum values.
     * 
     * @return string SQL code.
     */
    public function enum(array $values): string
    {
        $commaSeparatedValues = "'" . implode("', '", $values) . "'";

        return "ENUM({$commaSeparatedValues})";
    }
}
