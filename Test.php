<?php

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

print_r($container->make('foo'));
