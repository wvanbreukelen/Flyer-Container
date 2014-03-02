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

$container->singleton('bar', function()
{
	return new Bar();
});


//print_r($container->make('foo'));

$bar = $container->make('bar');

$bar->set('haha');

print_r($bar);

print_r($container->make('bar'));
