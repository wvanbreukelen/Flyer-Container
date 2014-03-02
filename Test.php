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
	public function __construct()
	{

	}
}


require('Container.php');

$container = new Flyer\Components\Container();

$container->bind('foo', function()
{
	return new Foo();
});

$bar = new Bar();

$container->instance($bar);

print_r($container->make('foo'));
