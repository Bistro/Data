<?php

class CollectionTest extends PHPUnit_Framework_TestCase
{
	public $collection;

	public function setUp()
	{
		$this->collection = new \Bistro\Data\Collection;
	}

	public function testEmptyOnInit()
	{
		$this->assertSame(0, $this->collection->count());
	}

	public function testAddOneRetrieve()
	{
		$this->collection->addOne('Dave');

		$this->assertSame(1, $this->collection->count());
		$this->assertSame('Dave', $this->collection->at(0));
	}

	public function testAdd()
	{
		$this->collection->add(array(
			'Dave',
			'Ana',
			'Nicholas'
		));

		$this->assertSame(3, $this->collection->count());
		$this->assertSame('Nicholas', $this->collection->at(2));
	}

	public function testReplace()
	{
		$this->collection->add(array(0, 1, 2));
		$this->collection->replace(array(3, 4));

		$this->assertSame(2, $this->collection->count());
	}

	public function testReset()
	{
		$this->collection->add(array(0, 1, 2));
		$this->collection->reset();

		$this->assertSame(0, $this->collection->count());
	}

	public function testToArray()
	{
		$data = array(0, 1, 2);

		$this->collection->add($data);
		$this->assertSame($data, $this->collection->toArray());
	}

	public function testToArrayWithHashes()
	{
		$data = array(
			'name' => "Dave",
			'job' => "Web Developer"
		);

		$this->collection->add(array(
			new \Bistro\Data\Hash($data)
		));

		$this->assertSame(array($data), $this->collection->toArray());
	}

	public function testToJSON()
	{
		$this->collection->add(array(4, 5, 6));
		$this->assertSame("[4,5,6]", $this->collection->toJSON());
	}

	public function testToJSONWithHash()
	{
		$data = array(
			'name' => "Dave",
			'job' => "Web Developer"
		);

		$this->collection->add(array(
			new \Bistro\Data\Hash($data)
		));

		$this->assertSame('[{"name":"Dave","job":"Web Developer"}]', $this->collection->toJSON());
	}

	public function testArrayAccess()
	{
		$this->collection[0] = 'Foo';
		$this->assertSame('Foo', $this->collection[0]);
	}

}
