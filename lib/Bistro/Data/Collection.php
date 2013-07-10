<?php

namespace Bistro\Data;

class Collection implements \ArrayAccess, \IteratorAggregate, \Countable
{
	/**
	 * @var array  The list of items in the collection
	 */
	protected $items = array();

	/**
	 * @param array $items An array of items to add to the collection
	 */
	public function __construct(array $items = array())
	{
		$this->add($items);
	}

	/**
	 * @param  mixed $item  Adds one item to the collection
	 * @return \Bistro\Data\Collection
	 */
	public function addOne($item)
	{
		$this->items[] = $item;
		return $this;
	}

	/**
	 * @param  array $items   An array of items to add to the collection
	 * @return \Bistro\Data\Collection
	 */
	public function add(array $items)
	{
		$this->items = \array_merge($this->items, $items);
		return $this;
	}

	/**
	 * @param  int $index  The index of the item to grab. 0 indexed
	 * @return mixed
	 */
	public function at($index)
	{
		return $this->offsetGet($index);
	}

	/**
	 * @param  array  $items The items to replace
	 * @return \Bistro\Data\Collection
	 */
	public function replace(array $items)
	{
		$this->reset();
		$this->add($items);
	}

	/**
	 * @return \Bistro\Data\Collection
	 */
	public function reset()
	{
		$this->items = array();
		return $this;
	}

	/**
	 * @return array  Returns the collection as an array. If the items are \Bistro\Data\Hash
	 *                then they will be converted to arrays as well.
	 */
	public function toArray()
	{
		$tmp = array();

		return \array_map(function($item){
			if ($item instanceof Hash)
			{
				$item = $item->toArray();
			}

			return $item;
		}, $this->items);
	}

	/**
	 * @return string  A json representation of the collection
	 */
	public function toJSON()
	{
		return \json_encode($this->toArray());
	}

/** ==============
    ArrayAccess
	============== */
	public function offsetExists($offset)
	{
		return isset($this->items[$offset]);
	}

	public function offsetGet($offset)
	{
		return $this->items[$offset];
	}

	public function offsetSet($offset, $value)
	{
		$this->items[$offset] = $value;
	}

	public function offsetUnset($offset)
	{
		unset($this->items[$offset]);
	}

/** ====================
    IteratorAggregate
	==================== **/
	public function getIterator()
	{
		return new \ArrayIterator($this->items);
	}

/** =================
    Countable
	================= **/
	public function count()
	{
		return count($this->items);
	}

}
