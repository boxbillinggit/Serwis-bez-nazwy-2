<?php defined('SYSPATH') or die('No direct script access.');

class Webcms_DB {

	/**
	 * Create a new database query of the given type.
	 *
	 * @param   integer  type: Database::SELECT, Database::UPDATE, etc
	 * @param   string   SQL statement
	 * @return  Database_Query
	 */
	public static function query($type, $sql)
	{
		return new Database_Query($type, $sql);
	}

	/**
	 * Create a new SELECT builder.
	 *
	 * @param   mixed   column name or array($column, $alias) or object
	 * @param   ...
	 * @return  Database_Query_Builder_Select
	 */
	public static function select($columns = NULL)
	{
		return new Database_Query_Builder_Select(func_get_args());
	}

	/**
	 * Create a new SELECT builder from an array of columns
	 *
	 * @param   array   columns to select
	 * @return  Database_Query_Builder_Select
	 */
	public static function select_array(array $columns = NULL)
	{
		return new Database_Query_Builder_Select($columns);
	}

	/**
	 * Create a new INSERT builder.
	 *
	 * @param   string  table to insert into
	 * @param   array   list of column names or array($column, $alias) or object
	 * @return  Database_Query_Builder_Insert
	 */
	public static function insert($table, array $columns = NULL)
	{
		return new Database_Query_Builder_Insert($table, $columns);
	}

	/**
	 * Create a new UPDATE builder.
	 *
	 * @param   string  table to update
	 * @return  Database_Query_Builder_Update
	 */
	public static function update($table)
	{
		return new Database_Query_Builder_Update($table);
	}

	/**
	 * Create a new DELETE builder.
	 *
	 * @param   string  table to delete from
	 * @return  Database_Query_Builder_Delete
	 */
	public static function delete($table)
	{
		return new Database_Query_Builder_Delete($table);
	}

	/**
	 * Create a new database expression. Database expressions are not escaped,
	 * which allows for complex and/or database-specific features to be used
	 * by all builder classes.
	 *
	 * @param   string  expression
	 * @return  Database_Expression
	 */
	public static function expr($string)
	{
		return new Database_Expression($string);
	}

} // End DB