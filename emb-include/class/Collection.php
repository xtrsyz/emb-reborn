<?php

/**
 * Collection Class
 *
 * stdClass with some additional methods and with no undefined property notice.
 *
 * @package    EmbuhEngine
 * @author     errorisme <errorisme@gmail.com>
 * @copyright  2023 (c) EmbuhTeam
 * @license    Private
 * @version    1.0.2
 * @modified   2024-04-16
 */
class Collection extends \stdClass implements JsonSerializable {
	const VERSION = '1.0.2';

	/**
     * Constructor method for initializing the collection.
     *
     * @param array $data An associative array to populate the collection.
     */
	function __construct(array $data = []) {
		foreach ($data as $key => $value) {
			$this->{$key} = $value;
		}
	}

	/**
     * Magic getter method to access properties of the collection.
     *
     * @param string $key The name of the property to retrieve.
     * @return mixed|null The value of the property if it exists, or null otherwise.
     */
	function __get($key) {
		return isset($this->{$key}) ? $this->{$key} : null;
	}

	/**
     * Magic caller method to invoke callable properties of the collection.
     *
     * @param string $key The name of the callable property to invoke.
     * @param array $args An array of arguments to pass to the callable.
     * @return mixed|null The result of the callable if it exists, or null otherwise.
     */
	function __call($key, $args) {
		if (isset($this->{$key}) && is_callable($this->{$key})) {
			return call_user_func_array($this->{$key}, $args);
		}
		return null;
	}

	/**
     * Get method to retrieve the collection's properties or a specific property value.
     *
     * @param string|null $key The name of the property to retrieve (optional).
     * @return mixed|array|null The value of the specified property, all properties, or null if not found.
     */
	// function get($key = null) {
	// 	if ($key) {
	// 		return isset($this->{$key}) ? $this->{$key} : null;
	// 	}
	// 	return get_object_vars($this);
	// }
	function get($prefix = null) {
		if ($prefix) {
			return array_filter(get_object_vars($this), function($value, $key) use($prefix) {
				return strpos($key, $prefix) === 0;
			}, ARRAY_FILTER_USE_BOTH);
		}
		return get_object_vars($this);
	}

	/**
     * Set method to replace the collection's data with a new set of properties.
     *
     * @param array $data An associative array to replace the collection's properties.
     */
	function set(array $data) {
		foreach (get_object_vars($this) as $property => $value) {
			unset($this->$property);
		}
		foreach ($data as $key => $value) {
			$this->{$key} = $value;
		}
	}

	/**
     * Append method to add new properties to the collection.
     *
     * @param array $data An associative array to add as properties to the collection.
     */
	function append(array $data) {
		foreach ($data as $key => $value) {
			$this->{$key} = $value;
		}
	}

	/**
     * Map each item in the collection using the provided callable function.
     *
     * @param callable $callable The callback function to apply to each element.
     * @return Collection A new Collection instance with the mapped values.
     */
	function map(Callable $callable) {
		$className = get_called_class();
		return new $className(array_map($callable, get_object_vars($this)));
	}

	/**
     * Apply a callable function to each element in the collection in-place.
     *
     * @param callable $callable The callback function to apply to each element.
     * @return $this The modified collection object.
     */
	function apply(Callable $callable) {
		foreach(get_object_vars($this) as $key => $value) {
			$this->{$key} = call_user_func($callable, $value, $key);
		}
		return $this;
	}

	/**
     * Filter the elements in the collection using a callable function.
     *
     * @param callable $callable The callback function used to filter elements.
     * @return Collection A new Collection instance containing the filtered elements.
     */
	public function filter(Callable $callable, $flag = ARRAY_FILTER_USE_BOTH) {
		$className = get_called_class();
		return new $className(array_filter(get_object_vars($this), $callable, $flag));
	}

    public function __toString() {
        return print_r($this, true);
    }

	public function jsonSerialize() {
		return get_object_vars($this);
	}
}
