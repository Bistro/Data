<?php

namespace Bistro\Data\Query;

/**
 * Building a DELETE query.
 *
 * See Select for the full rant, but the tl;dr is that php 4 is messing up my
 * classes, so I have to actually declare where() and limit() in this class
 * instead of just using the cool passthru functionality.
 */
class Delete extends Query
{
	/**
	 * @var Bistro\Data\Query\Where  The where object
	 */
	protected $where = null;

	/**
	 * @var \Bistro\Data\Query\Order  The order object
	 */
	protected $order_by = null;

	/**
	 * @var \Bistro\Data\Query\Limit  The limit object
	 */
	protected $limit = null;

	/**
	 * @var array  A list of mixins that the query can passthru
	 */
	protected $mixins = array('where', 'order_by', 'limit');

	/**
	 * Optionally sets the table name and initializes the internal class
	 * properties.
	 *
	 * @param string $table  The name of the table
	 */
	public function __construct($table = null)
	{
		$this->where = new \Bistro\Data\Query\Where;
		$this->order_by = new \Bistro\Data\Query\Order;
		$this->limit = new \Bistro\Data\Query\Limit;

		parent::__construct($table);
	}

	/**
	 * Adds a WHERE clause.
	 *
	 * @param  string $column  The column name
	 * @param  string $op      The comparison operator
	 * @param  mixed  $value   The value to bind
	 * @return \Bistro\Data\Query\Delete
	 */
	public function where($column, $op, $value)
	{	
		$this->where->andWhere($column, $op, $value);
		return $this;
	}

	/**
	 * Sets the limit.
	 *
	 * @param  int $num  The number to limit the queries to
	 * @return \Bistro\Data\Query\Select
	 */
	public function limit($num)
	{
		$this->limit->setLimit($num);
		return $this;
	}

	/**
	 * Gets all of the bound parameters for this query.
	 *
	 * @return array
	 */
	public function getParams()
	{
		/**
		 * Instead of writing some crazy magic foo to pull the params
		 * automatically, I'm just overridding this function to get the
		 * correct params.
		 *
		 * Since where is the only clause that would have params, let's just
		 * fetch those
		 */
		return $this->where->getParams();
	}

	/**
	 * Compiles the query into raw SQL
	 *
	 * @return  string
	 */
	public function compile()
	{
		$sql = array("DELETE FROM");
		$sql[] = $this->table;

		$sql = \array_merge($sql, $this->compileMixins());

		return join(' ', $sql);
	}

}
