<?php

class Bar
{

	protected $lol;

	public function set($value)
	{
		$this->lol = $value;
	}

	public function get()
	{
		return $this->lol;
	}
}

class Foo
{

	protected $bar;

	public function __construct(Bar $bar)
	{
		$this->bar = $bar;
	}

	public function getBar()
	{
		return $this->bar;
	}
}


require('Container.php');

$container = new Flyer\Components\Container();

print_r($container->make('foo'));

