<?php

class ModelTest extends PHPUnit_Framework_TestCase
{
	public $model;

	public function setUp()
	{
		parent::setUp();

		$this->model = new \Bistro\Data\Model;
	}

	public function testIsNew()
	{
		$this->assertTrue($this->model->isNew());
	}

	public function testIsNotNew()
	{
		$this->model->set(array(
			'id' => 1,
			'username' => 'dwidmer'
		));

		$this->assertFalse($this->model->isNew());
	}

	public function testGetModifiedData()
	{
		$data = array(
			'username' => 'dwidmer',
			'password' => 'youllneverguess'
		);

		$this->model->set($data);
		$this->assertSame($data, $this->model->getModifiedData());
	}

	public function testResetClearsModifiedData()
	{
		$data = array(
			'username' => 'dwidmer',
			'password' => 'youllneverguess'
		);

		$this->model->set($data);
		// save here IRL...
		$this->model->reset();

		$this->assertSame(array(), $this->model->getModifiedData());
	}

}