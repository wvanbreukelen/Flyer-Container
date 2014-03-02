<?php namespace Flyer\Components;

use ReflectionClass;
use ReflectionParameter;

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
	 * Contains all of the bindings options
	 */

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
	 * Bind a existing instance into the container
	 */
	
	public function instance($instance)
	{
		if (is_object($instance))
		{
			$this->instances[strtolower(get_class($instance))] = $instance;
		}
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
		 	//$concrete = $this->instances[$abstract] = $this->bindings[$abstract]();

		 	return $this->build('Foo');
		}


		return false;
	}

	public function build($concrete)
	{
		$reflector = new ReflectionClass($concrete);
		$constructor = $reflector->getConstructor();

		if (is_null($constructor))
		{
			//echo 1;
			return $concrete;
		}

		$dependencies = $constructor->getParameters();
		
		$instances = $this->getDependencies($dependencies);

		return $reflector->newInstanceArgs($instances);

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

	protected function getDependencies($parameters)
	{
		$dependencies = array();
		
		foreach ($parameters as $parameter)
		{
			$dependency = $parameter->getClass();
			if (!is_null($dependency))
			{
				$dependencies[] = $this->resolveClass($parameter);
			}
		}

		//print_r($dependencies);

		return (array) $dependencies;
	}

	/**
	 * Resolve a class baed dependency from the container
	 */

	protected function resolveClass(ReflectionParameter $parameter)
	{
		try
		{
			//return $this->make($parameter->getClass()->name);
			$name = $parameter->getClass()->name;
			return new $name;
		} catch (\Exception $e) {
			throw "Error: " .  $e;
		}
	}
}