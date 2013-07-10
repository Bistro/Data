<?php

namespace Bistro\Data;

class Table
{
	/**
	 * @var \Bistro\Data\Adapter
	 */
	protected $adapter;

	/**
	 * @var string  The name of the table
	 */
	public $name;

	/**
	 * @var string  The primary key
	 */
	public $primary_key;

	/**
	 * @var string  The name of the model class to generate when selecting rows
	 */
	public $model = '\\Bistro\\Data\\Model';

	/**
	 * @var array   A listing of columns in the table
	 */
	public $columns = array();

	/**
	 * A list of options that can be set is below.
	 *
	 * Name        | Description
	 * ------------|------------
	 * name        | The table name
	 * primary_key | The primary key column
	 * model       | The model class to transform results to
	 * columns     | A whitelist of columns for the database
	 * 
	 * @param \Bistro\Data\Adapter  $adapter   An adapter for data
	 * @param array                 $options   Any additional table options (see above)
	 */
	public function __construct($adapter, $options = array())
	{
		$this->adapter = $adapter;

		foreach ($options as $key => $value)
		{
			$this->{$key} = $value;
		}
	}

	/**
	 * Attempt to get a row of data by using the passed in key.
	 *
	 * @param  mixed $key  The value of the primary key that is used to find the data
	 * @return mixed       The model class for the row or null
	 */
	public function get($key)
	{
		$query = new \Bistro\Data\Query\Select($this->name);
		$query->where($this->primary_key, '=', $key);

		$result = $this->adapter->select($query->compile(), $query->getParams());

		if (empty($result))
		{
			return null;
		}

		$collection = $this->formatResult($result);
		return $collection[0];
	}

	/**
	 * Get all of the records in the data set.
	 *
	 * @param  string $column     The column to sort on
	 * @param  string $direction  The direction to sort on (ASC or DESC)
	 * @return \Bistro\Data\Collection
	 */
	public function all($column = null, $direction = null)
	{
		if ($column === null)
		{
			$column = $this->primary_key;
		}

		$query = new \Bistro\Data\Query\Select($this->name);
		$query->orderBy($column, $direction);

		$result = $this->adapter->select($query->compile());

		return $this->formatResult($result);
	}

	/**
	 * Gets all of the records that satisfy the search parameters.
	 *
	 * @param  array $params   The search parameters
	 * @return \Bistro\Data\Collection
	 */
	public function find(array $params)
	{
		$query = new \Bistro\Data\Query\Select($this->name);
		foreach ($params as $key => $value)
		{
			$query->where($key, '=', $value);
		}

		$result = $this->adapter->select($query->compile(), $query->getParams());

		return $this->formatResult($result);
	}

	/**
	 * Saves an entity.
	 *
	 * @param \Bistro\Data\Model $model  The model to save
	 * @return mixed                     create = array($id, $affected) | update = $affected
	 */
	public function save(& $model)
	{
		return ($model->isNew()) ?
			$this->create($model) :
			$this->update($model) ;
	}

	/**
	 * Creates a new row.
	 *
	 * @param  \Bistro\Data\Model $model  The model to create
	 * @return array                      array($id, $affected)
	 */
	protected function create(& $model)
	{
		$data = $this->prepareData($model);

		$query = new \Bistro\Data\Query\Insert($this->name);
		$query->columns(array_keys($data))->values(array_values($data));

		$result = $this->adapter->insert($query->compile(), $query->getParams());

		$model = $this->get($result[0]);

		return $result;
	}

	/**
	 * Updates an existing entity.
	 *
	 * @param  \Bistro\Data\Model $model  The model to update
	 * @return int                        Affected rows
	 */
	protected function update(& $model)
	{
		$data = $this->prepareData($model);

		if (empty($data))
		{
			$model->reset();
			return 0;
		}

		$query = new \Bistro\Data\Query\Update($this->name);
		$query->set($data)->where($this->primary_key, '=', $model->{$this->primary_key});

		$result = $this->adapter->update($query->compile(), $query->getParams());

		$model->reset();
		return $result;
	}

	/**
	 * Deletes an entity.
	 *
	 * @param  \Bistro\Data\Model $model  The entity to delete
	 * @return boolean                    Was the delete successful?
	 */
	public function delete(& $model)
	{
		$query = new \Bistro\Data\Query\Delete($this->name);
		$query->where($this->primary_key, '=', $model->{$this->primary_key});

		$affected = $this->adapter->delete($query->compile(), $query->getParams());
		$success = $affected > 0;

		if ($success)
		{
			$model = null;
		}

		return $success;
	}

	/**
	 * @param  array $result  An array of results
	 * @return \Bistro\Data\Collection
	 */
	protected function formatResult(array $result)
	{
		$collection = new \Bistro\Data\Collection;

		foreach ($result as $row)
		{
			$model = new $this->model;
			$model->set($row);
			$model->reset();
			$collection->add($model);
		}

		return $collection;
	}

	/**
	 * @param  \Bistro\Data\Model $model  The model to prepare
	 * @return array
	 */
	protected function prepareData($model)
	{
		$data = $model->getModifiedData();
		return \array_intersect_key($data, \array_flip($this->columns));
	}

}
