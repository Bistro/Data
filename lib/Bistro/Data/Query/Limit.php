<?php

namespace Bistro\Data\Query;

/**
 * The LIMIT # clause builder.
 */
class Limit implements Builder, Mixin
{
	/**
	 * @var int  The limit number
	 */
	private $limit = null;

	/**
	 * Sets the limit.
	 *
	 * @param int $num  The number to limit the queries to
	 */
	public function setLimit($num)
	{
		$this->limit = (int) $num;
	}

	/**
	 * Compiles the query into raw SQL
	 *
	 * @return  string
	 */
	public function compile()
	{
		if ($this->limit === null)
		{
			return "";
		}

		return "LIMIT {$this->limit}";
	}

	/**
	 * Gets all of the methods that should be passed as "mixin" methods.
	 *
	 * @return array
	 */
	public function getMethods()
	{
		return array();
	}

}
