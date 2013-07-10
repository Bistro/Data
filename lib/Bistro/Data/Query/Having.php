<?php

namespace Bistro\Data\Query;

/**
 * A class to build HAVING clauses.
 */
class Having extends Condition implements Mixin
{
	/**
	 * Gets the type of sorting query we are running.
	 *
	 * @return string
	 */
	public function getType()
	{
		return "HAVING";
	}

	/**
	 * Builds an AND HAVING clause.
	 *
	 * @param  string $column  The column name
	 * @param  string $op      The comparison operator
	 * @param  mixed  $value   The value to bind
	 * @return \Bistro\Data\Query\Having
	 */
	public function andHaving($column, $op, $value)
	{
		$this->clauses[] = array("AND", $column, $op, $value);
		return $this;
	}

	/**
	 * Builds an OR HAVING clause.
	 *
	 * @param  string $column  The column name
	 * @param  string $op      The comparison operator
	 * @param  mixed  $value   The value to bind
	 * @return \Bistro\Data\Query\Having
	 */
	public function orHaving($column, $op, $value)
	{
		$this->clauses[] = array("OR", $column, $op, $value);
		return $this;
	}

	/**
	 * Gets all of the methods that should be passed as "mixin" methods.
	 *
	 * @return array
	 */
	public function getMethods()
	{
		return array('andHaving', 'orHaving');
	}

}
