<?php

namespace Bistro\Data\Adapter;

class PDO
{
	/**
	 * @var \PDO  The PDO connection object
	 */
	private $pdo = null;

	/**
	 * @var array An internal list of queries run
	 */
	private $queries = array();

	/**
	 * Creates a new PDO Adapter with the connection information passed in.
	 *
	 * @param \PDO $pdo  The PDO connection information.
	 */
	public function __construct(\PDO $pdo)
	{
		$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		$pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
		$this->pdo = $pdo;
	}

	/**
	 * Runs a query to find data in the dataset.
	 *
	 * @param  string $query   The query to run.
	 * @param  array  $data    An array of data to bind to the query
	 * @param  array  $params  A list of parameters to bind to the query
	 * @return array           The result set from the query
	 */
	public function select($query, array $params = array())
	{
		list($statement) = $this->run($query, $params);
		return $statement->fetchAll(\PDO::FETCH_ASSOC);
	}

	/**
	 * Runs a query that will add data to the dataset
	 *
	 * @param  string $query  The query to run.
	 * @param  array  $params A list of parameters to bind to the query
	 * @return array          array($insert_id, $affected_rows);
	 */
	public function insert($query, array $params = array())
	{
		list($statement) = $this->run($query, $params);
		return array((int) $this->pdo->lastInsertId(), $statement->rowCount());
	}

	/**
	 * Runs a query that will update data
	 *
	 * @param  string $query  The query to run
	 * @param  array  $params A list of parameters to bind to the query
	 * @return int            The number of affected rows
	 */
	public function update($query, array $params = array())
	{
		list($statement) = $this->run($query, $params);
		return $statement->rowCount();
	}

	/**
	 * Runs a query that will remove data.
	 *
	 * @param  string $query  The query to run
	 * @param  array  $params A list of parameters to bind to the query
	 * @return int            The number of deleted rows 
	 */
	public function delete($query, array $params = array())
	{
		list($statement) = $this->run($query, $params);
		return $statement->rowCount();
	}

	/**
	 * Runs a raw query.
	 *
	 * @param  string $query The query
	 * @return boolean       Success
	 */
	public function query($query, array $params = array())
	{
		list($statement, $success) = $this->run($query, $params);
		return $success;
	}

	/**
	 * Gets a list of all of the queries that have been run.
	 *
	 * @return array
	 */
	public function getQueries()
	{
		return $this->queries;
	}

	/**
	 * Runs a SQL query.
	 *
	 * @param  string $query  The SQL query to run
	 * @return array          [\PDOStatement, (boolean) success]
	 */
	private function run($query, array $params)
	{
		$this->queries[] = $query;

		$statement = $this->pdo->prepare($query);
		$success = $statement->execute($params);

		return array($statement, $success);
	}

}
