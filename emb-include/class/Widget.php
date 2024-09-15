<?php

/**
 * Widget
 *
 * Extends from Collection class.
 *
 * @package    EmbuhEngine
 * @author     errorisme <errorisme@gmail.com>
 * @copyright  2024 (c) EmbuhTeam
 * @license    Private
 * @require    Collection.php v1.0.0
 * @version    1.0.8
 * @modified   2024-03-19
*/
class Widget {
	const VERSION = '1.0.8';
	protected $data = [];
	private $_group, $_name;

	function __construct(array $data = []) {
		$this->data = $data;
	}
	function __get($key) {
		return isset($this->data[$key]) ? $this->data[$key] : null;
	}
	function __set($key, $value) {
		$this->data[$key] = $value;
	}
	function __unset($key) {
		unset($this->data[$key]);
	}
	function __call($key, $args) {
		if(isset($this->data[$key]) && is_callable($this->data[$key]))
			return call_user_func_array($this->data[$key], $args);
		elseif(isset($args[0]))
			return  isset($this->data[$key]) ? $this->data[$key] : $args[0];
		return null;
	}
	/* @since 1.0.1 */
	function append($group, $name, $title = null, $content = null) {
		$this->{$group} = array_merge($this->{$group} ?: [], [$name => ['title' => '', 'content' => []]]);
		$this->_group = $group;
		$this->_name = $name;
		if($title) $this->setTitle($title);
		if($content) $this->setContent($content);
		return $this;
	}
	function prepend($group, $name, $title = null, $content = null) {
		$this->{$group} = array_merge([$name => ['title' => '', 'content' => []]], $this->{$group} ?: []);
		$this->_group = $group;
		$this->_name = $name;
		if($title) $this->setTitle($title);
		if($content) $this->setContent($content);
		return $this;
	}
	function register($group, $name, $title = null, $content = null, $on_top = false) {
		if($on_top)
			return $this->prepend($group, $name, $title, $content);
		else
			return $this->append($group, $name, $title, $content);
	}
	/* @since 1.0.1 */
	function unregister($group, $name = null) {
		if($name)
			unset($this->data[$group][$name]);
		else
			unset($this->data[$group]);
		return $this;
	}
	/* @since 1.0.1 */
	function group($group) {
		$this->_group = $group;
		if(!isset($this->data[$this->_group]))
			$this->{$this->_group} = [];
		return $this;
	}
	/* @since 1.0.8 */
	function name($name) {
		$this->_name = $name;
		return $this;
	}
	/* @since 1.0.1 */
	function setTitle($title) {
		if(!$this->_group || !$this->_name) return $this;
		$this->data[$this->_group][$this->_name]['title'] = $title;
		return $this;
	}
	/* @since 1.0.1 */
	function setContent($content) {
		if(!$this->_group || !$this->_name) return $this;
		$this->data[$this->_group][$this->_name]['content'] = $content;
		return $this;
	}
	/* @since 1.0.8 */
	function setAttr($key, $value) {
		if(!$this->_group || !$this->_name) return $this;
		$this->data[$this->_group][$this->_name][$key] = $value;
		return $this;
	}
	/* @since 1.0.1 */
	function addItem($item) {
		if(!$this->_group || !$this->_name) return $this;
		if(!isset($this->data[$this->_group]))
			$this->{$this->_group} = [];
		if(!isset($this->data[$this->_group][$this->_name]['content']))
			$this->data[$this->_group][$this->_name]['content'] = [];
		if(is_array($this->data[$this->_group][$this->_name]['content']))
			$this->data[$this->_group][$this->_name]['content'][] = $item;
		return $this;
	}
	/* @since 1.0.2 */
	function addLink($text, $href, $extras = []) {
		$this->addItem(array_merge(['text' => $text, 'href' => $href], $extras));
		return $this;
	}
	/* @since 1.0.8 */
	function set(...$args) {
		// ->set($group, $name)
		if(!empty($args[1])) list($this->_group, $this->_name) = $args;
		// ->set($name)
		elseif(!empty($args[0])) list($this->_name) = $args;
		if($this->_group && !isset($this->data[$this->_group]))
			$this->{$this->_group} = [];
		return $this;
	}
	/* @since 1.0.7 */
	function get(...$args) {
		$this->set(...$args);
		return isset($this->data[$this->_group][$this->_name]) ? $this->data[$this->_group][$this->_name] : [];
	}
	/* @since 1.0.3 */
	function getTitle(...$args) {
		$this->set(...$args);
		return isset($this->data[$this->_group][$this->_name]['title']) ? $this->data[$this->_group][$this->_name]['title'] : '';
	}
	/* @since 1.0.3 */
	function getContent(...$args) {
		$this->set(...$args);
		return isset($this->data[$this->_group][$this->_name]['content']) ? $this->data[$this->_group][$this->_name]['content'] : [];
	}
	/* @since 1.0.4 */
	function toTop(...$args) {
		$this->set(...$args);
		if(isset($this->data[$this->_group][$this->_name])) {
			$value = $this->data[$this->_group][$this->_name];
			unset($this->data[$this->_group][$this->_name]);
			$this->{$this->_group} = [$this->_name => $value] + $this->{$this->_group};
		}
		return $this;
	}
	/* @since 1.0.4 */
	function toEnd(...$args) {
		$this->set(...$args);
		if(isset($this->data[$this->_group][$this->_name])) {
			$value = $this->data[$this->_group][$this->_name];
			unset($this->data[$this->_group][$this->_name]);
			$this->data[$this->_group][$this->_name] = $value;
		}
		return $this;
	}
	/* @since 1.0.7 */
	function itemToTop(...$args) {
		$this->set(...$args);
		if(isset($this->data[$this->_group][$this->_name]['content']) && is_array($this->data[$this->_group][$this->_name]['content']))
			array_unshift($this->data[$this->_group][$this->_name]['content'], array_pop($this->data[$this->_group][$this->_name]['content']));
		return $this;
	}
	/* @since 1.0.7 */
	function getLastItem(...$args) {
		$this->set(...$args);
		if(isset($this->data[$this->_group][$this->_name]['content']) && is_array($this->data[$this->_group][$this->_name]['content']))
			return end($this->data[$this->_group][$this->_name]['content']);
	}
}
