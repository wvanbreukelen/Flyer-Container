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

	protected $options = array();

	/**
	 * Bind a closure into the container
	 */
	
	public function bind($abstract, $closure, $options = array())
	{
		//$className = $this->resolveClassName($closure());
		$this->bindings[$abstract] = $closure;
		$this->setOptions($abstract, $options);
	}

	/**
	 * Bind a singleton into the container
	 */
	
	public function singleton($abstract, $closure)
	{

		$this->bind($abstract, $closure, array('singleton' => true));
	}

	/**
	 * Make a binding
	 */

	public function make($abstract)
	{
		if ($this->isBuildable($abstract))
		{
			if ($this->isSingleton($abstract))
			{
				return $this->instances[$abstract];
			}
		 	return $this->instances[$abstract] = $this->bindings[$abstract](); 
		}
		return false;
	}

	protected function isSingleton($abstract)
	{
		if (isset($this->instances[$abstract]) && $this->resolveOption($abstract, 'singleton') == true)
		{
			return true;
		}
		return false;
	}

	protected function setOptions($abstract, $options)
	{
		if (count($options) < 1) return;

		$this->options[$abstract] = $options;
	}

	protected function resolveOption($abstract, $option)
	{
		if (isset($this->options[$abstract][$option]))
		{
			return $this->options[$abstract][$option];
		}
		return;
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