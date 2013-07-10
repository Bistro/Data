<?php

namespace Bistro\Data\Query;

/**
 * An interface for Query builder classes.
 */
interface Builder
{
	/**
	 * Compiles the query into raw SQL
	 *
	 * @return  string
	 */
	public function compile();
}
