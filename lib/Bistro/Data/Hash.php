<?php

namespace Bistro\Data;

class Hash implements \ArrayAccess, \Countable, \IteratorAggregate
{
	/**
	 * @var array  The internal data array
	 */
	protected $data;

	/**
	 * @param array $data  Any initial data
	 */
	public function __construct(array $data = array())
	{
		$this->replace($data);
	}

	/**
	 * Checks to see if a key exists.
	 *
	 * @param  string $key  The key to check for
	 * @return boolean
	 */
	public function has($key)
	{
		return $this->offsetExists($key);
	}

	/**
	 * @param  string $name    The property name to get
	 * @param  mixed  $default The default value if the property isn't found
	 * @return mixed           The property value or default
	 */
	public function get($name, $default = null)
	{
		if ($this->offsetExists($name))
		{
			$default = $this->offsetGet($name);
		}

		return $default;
	}

	/**
	 * @param  mixed  $prop      The property name as string or an array of key => value pairs
	 * @param  mixed  $value     The property value
	 * @return \Bisto\Data\Hash  $this
	 */
	public function set($prop, $value = null)
	{
		if ( ! \is_array($prop))
		{
			$prop = array($prop => $value);
		}

		foreach ($prop as $key => $value)
		{
			$this->offsetSet($key, $value);
		}

		return $this;
	}

	/**
	 * @param  string $key  The property name
	 * @return \Bisto\Data\Hash  $this
	 */
	public function delete($key)
	{
		$this->offsetUnset($key);
		return $this;
	}

	/**
	 * Alias for count.
	 *
	 * @return int The number of elements
	 */
	public function length()
	{
		return $this->count();
	}

	/**
	 * @param  array  $data  The data to merge into the hash
	 * @return \Bistro\Data\Hash  $this
	 */
	public function merge(array $data)
	{
		$this->data = \array_merge($this->data, $data);
		return $this;
	}

	/**
	 * @param  array  $data  The new data array
	 * @return \Bisto\Data\Hash   $this
	 */
	public function replace(array $data)
	{
		$this->data = $data;
		return $this;
	}

	/**
	 * Clears out the data.
	 *
	 * @return \Bisto\Data\Hash  $this
	 */
	public function clear()
	{
		$this->data = array();
		return $this;
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		return $this->data;
	}

	/**
	 * @see    http://www.php.net/manual/en/function.json-encode.php
	 * @param  int $options  The JSON options constant
	 * @return string        A json encoded string
	 */
	public function toJSON($options = 0)
	{
		return json_encode($this->data, $options);
	}

/** ====================
    Object Access
    ==================== **/

    /**
     * @param string $propery  The property to access
     */
	public function __get($property)
	{
		return $this->offsetGet($property);
	}

	/**
	 * @param string $property The property to set
	 * @param mixed  $value    The value to set
	 */
	public function __set($property, $value)
	{
		$this->offsetSet($property, $value);
	}

/** ====================
	ArrayAccess
	==================== **/

	/**
	 * @param  mixed  $offset  The offest name
	 * @return boolean
	 */
	public function offsetExists($offset)
	{
		return \array_key_exists($offset, $this->data);
	}

	/**
	 * @param  mixed  $offset The offset to find
	 * @return mixed          The offset value
	 */
	public function offsetGet($offset)
	{
		return $this->data[$offset];
	}

	/**
	 * @param  mixed  $offset The property name
	 * @param  mixed  $value  The property value
	 */
	public function offsetSet($offset, $value)
	{
		$this->data[$offset] = $value;
	}

	/**
	 * @param  mixed  $offset  The property name
	 */
	public function offsetUnset($offset)
	{
		unset($this->data[$offset]);
	}

/** ====================
	Countable
	==================== */

	/**
	 * @return int  The number of elements in the hash
	 */
	public function count()
	{
		return \count($this->data);
	}

/** ====================
	IteratorAggregate
	==================== */

	/**
	 * @return \Traversable
	 */
	public function getIterator()
	{
		return new \ArrayIterator($this->data);
	}

}
