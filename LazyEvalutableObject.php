<?php
class LazyEvalutableObject {
	private static $_events = array();
	private static $_defaults = array();
	public function addLazyEvalutionObserver($property, $handler) {
		$className = get_class($this);
		if (property_exists($this, $property)) {
			if (is_callable($handler)) {
				self::$_defaults[$className][$property] = $this->{$property};
				unset($this->{$property});
				self::$_events[$className][$property] = $handler;
			} else {
				if (is_array($handler))
					$handler = sprintf('%s::%s', get_class($handler[0]), $handler[1]);
				trigger_error(sprintf('Observer (function) does not exist: %s()', $handler), E_USER_WARNING);
			}
		} else
			trigger_error(sprintf('Can\'t add observer at property: %s::$%s', $className, $property), E_USER_WARNING);
	}
	public function __get($property) {
		$className = get_class($this);
		if (array_key_exists($property, self::$_defaults[$className])) {
			$returnValue = null;
			if (isset(self::$_events[$className][$property])) {
				$handler = self::$_events[$className][$property];
				$returnValue = call_user_func($handler, $property);
			}
			if (!isset($this->{$property}) && $returnValue === null)
				return self::$_defaults[$className][$property];
			else
				return $returnValue;
		} else
			trigger_error(sprintf('Undefined property: %s::$%s', $className, $property), E_USER_NOTICE);
	}
}
