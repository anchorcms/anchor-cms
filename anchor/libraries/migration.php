<?php

use System\config;
use System\database as DB;

/**
 * migration class
 * Base class for database migrations. Allows to migrate up or down. The respective methods
 * have to be implemented by children classes
 */
abstract class migration
{
    /**
     * Apply the migration
     *
     * @return mixed
     */
    abstract public function up();

    /**
     * Rollback the migration
     *
     * @return mixed
     */
    abstract public function down();

    /**
     * Checks whether a column exists in the current schema
     *
     * @deprecated Use hasTableColumn() instead
     *
     * @param string $table  name of the table
     * @param string $column name of the column
     *
     * @return bool
     */
    public function has_table_column($table, $column)
    {
        return $this->hasTableColumn($table, $column);
    }

    /**
     * Checks whether a column exists in the current schema
     *
     * @param string $table  name of the table
     * @param string $column name of the column
     *
     * @return bool
     */
    public function hasTableColumn($table, $column)
    {
        if ($this->has_table($table)) {
            $sql = 'SHOW COLUMNS FROM `' . $table . '`';
            list($result, $statement) = DB::ask($sql);

            /** @var \PDOStatement $statement */
            $statement->setFetchMode(PDO::FETCH_OBJ);

            $columns = [];

            foreach ($statement->fetchAll() as $row) {
                $columns[] = $row->Field;
            }

            return in_array($column, $columns);
        } else {
            return false;
        }
    }

    /**
     * Checks whether a table exists in the schema
     *
     * @deprecated Use hasTable() instead
     *
     * @param string $table name of the table
     *
     * @return bool
     */
    public function has_table($table)
    {
        return $this->hasTable($table);
    }

    /**
     * Checks whether a table exists in the schema
     *
     * @param string $table name of the table
     *
     * @return bool
     */
    public function hasTable($table)
    {
        $default = Config::db('default');
        $db      = Config::db('connections.' . $default . '.database');

        $sql = 'SHOW TABLES FROM `' . $db . '`';

        /** @var \PDOStatement $statement */
        list($result, $statement) = DB::ask($sql);
        $statement->setFetchMode(PDO::FETCH_NUM);

        $tables = [];

        foreach ($statement->fetchAll() as $row) {
            $tables[] = $row[0];
        }

        return in_array($table, $tables);
    }
}
