<?php namespace Flyer\Components;

class Container
{

	/**
	 * All the container bindings
	 */
	
	protected $bindings = array();

	/**
	 * The container shared instances
	 */
	
	protected $instances = array();

	/**
	 * The aliases for the container bindings
	 */
	
	protected $aliases = array();

	/**
	 * Bind a instance into the container
	 */
	
	public function bind($abstract, $closure)
	{
		//$className = $this->resolveClassName($closure());

		$this->bindings[$abstract] = $closure;
	}

	public function make($abstract)
	{
		if ($this->isBuildable($abstract))
		{
		 	return $this->bindings[$abstract](); 
		}
		return false;
	}

	protected function isBuildable($abstract)
	{
		if (isset($this->bindings[$abstract]))
		{
			return true;
		}
		return false;
	}

	protected function alias($class, array $abstract = array())
	{
		if (is_object($class)) $class = $this->resolveClassName($class);

		$this->aliases[$class] = $abstract;
	}

	protected function resolveClassName($instance)
	{
		return get_class($instance);
	}
}