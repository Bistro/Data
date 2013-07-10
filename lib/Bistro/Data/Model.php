<?php

namespace Bistro\Data;

class Model extends Hash
{
	public $id_attribute = "id";

	/**
	 * @var array  All of the data that has been modified
	 */
	protected $modified = array();

	/**
	 * @return \Bistro\Data\Model
	 */
	public function reset()
	{
		$this->modified = array();
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function isNew()
	{
		return $this->get($this->id_attribute) === null;
	}

	/**
	 * @return array
	 */
	public function getModifiedData()
	{
		$data = array();

		foreach ($this->modified as $key)
		{
			$data[$key] = $this->get($key);
		}

		return $data;
	}

	/**
	 * @return array  An array of errors (empty array for valid data)
	 */
	public function validate()
	{
		return array();
	}

	/**
	 * @param mixed $offset
	 * @param mixed $value
	 */
	public function offsetSet($offset, $value)
	{
		if ( ! $this->offsetExists($offset) OR $this->offsetGet($offset) !== $value)
		{
			$this->modified[] = $offset;
			parent::offsetSet($offset, $value);
		}
	}

}
