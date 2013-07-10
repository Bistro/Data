<?php

namespace Bistro\Data\Query;

/**
 * A class to allow table altering.
 */
class Alter implements Builder
{
	/**
	 * @var string
	 */
	private $table = null;

	/**
	 * @var array
	 */
	private $conditions = array();

	/**
	 * @param string $table  The table name
	 */
	public function __construct($table = null)
	{
		$this->setTable($table);
	}

	/**
	 * @return string
	 */
	public function getTable()
	{
		return $this->table;
	}

	/**
	 * @param string $table The table name to drop.
	 * @return \Bistro\Data\Query\Alter
	 */
	public function setTable($table)
	{
		$this->table = $table;
		return $this;
	}

	/**
	 * @return array  A list of alter conditions
	 */
	public function getConditions()
	{
		return $this->conditions;
	}

	/**
	 * @param string|\Bistro\Data\Query\Column $column     The column name or \Bistro\Data\Query\Column column
	 * @return Bistro\Data\Query\Alter                     $this
	 */
	public function addColumn($column)
	{
		if ($column instanceof \Bistro\Data\Query\Column)
		{
			$column = $column->compile();
		}

		$this->conditions[] = array("ADD", $column, null);
		return $this;
	}

	/**
	 * @param  string $column The columns name
	 * @return \Bistro\Data\Query\Alter
	 */
	public function addPrimaryKey($column)
	{
		return $this->addIndex($column, "PRIMARY KEY");
	}

	/**
	 * 
	 * @param  string $column The column name to add as a key
	 * @param  string $type   The type of key to add
	 * @return \Bistro\Data\Query\Alter  $this
	 */
	public function addIndex($column, $type = "INDEX")
	{
		$this->conditions[] = array("ADD", $type, "({$column})");
		return $this;
	}

	/**
	 * @param  string $column The column name
	 * @return \Bistro\Data\Query\Alter  $this
	 */
	public function dropColumn($column)
	{
		$this->conditions[] = array("DROP", $column, "");
		return $this;
	}

	/**
	 * @return \Bistro\Data\Query\Alter  $this
	 */
	public function dropPrimaryKey()
	{
		$this->conditions[] = array("DROP", "PRIMARY", "KEY");
	}

	/**
	 * @param  string $index  The index
	 * @return \Bistro\Data\Query\Alter  $this
	 */
	public function dropIndex($index, $type = "INDEX")
	{
		$this->conditions[] = array("DROP", $type, $index);
		return $this;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws \Bistro\Data\Query\Exception
	 */
	public function compile()
	{
		$conditions = $this->getConditions();

		if (empty($conditions))
		{
			throw new \Bistro\Data\Exception("\Bistro\Data\Query\Alter is an empty statement");
		}

		$query = array("ALTER", "TABLE", $this->getTable());

		foreach ($conditions as $condition)
		{
			$query[] = rtrim(join(' ', $condition));
		}

		return join(' ', $query);
	}

}
