<?php

namespace Bistro\Data\Query;

/**
 * The ORDER BY clause builder.
 */
class Order extends Sort implements Mixin
{
	/**
	 * Gets the type of sorting query we are running.
	 *
	 * @return string
	 */
	public function getType()
	{
		return "ORDER BY";
	}

	/**
	 * Orders a result with an optional direction.
	 *
	 * @param  string $column     The column name
	 * @param  string $direction  The direction to sort on
	 * @return \Bistro\Data\Query\Order
	 */
	public function orderBy($column, $direction = null)
	{
		$this->clauses[] = array($column, $direction);
		return $this;
	}

	/**
	 * Gets all of the methods that should be passed as "mixin" methods.
	 *
	 * @return array
	 */
	public function getMethods()
	{
		return array('orderBy');
	}

}
