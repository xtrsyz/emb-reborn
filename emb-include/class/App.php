<?php

/**
 * EmbuhEngine Main App Class
 *
 * Microframework designed for AGC websites.
 *
 * @package    EmbuhEngine
 * @author     errorisme <errorisme@gmail.com>
 * @copyright  2020 (c) EmbuhTeam
 * @license    Private
 * @require    Collection.php
 * @version    1.4.4
 * @modified   2024-07-02
 */
class App {
	const VERSION = '1.4.4';
	public $protocol, $hostname, $domain, $basepath, $baseurl, $homepath, $urlpath, $path, $fullpath,
	$prop = [],
	$part = [],
	$rules = ['default' => '404'],
	$rule, $route;
	protected $action = [], $filter = [];

	// magic
	public function __construct($config = []) {
		$this->addProperty('params', new Collection);
		$this->addProperty('config', new Collection($config));
		$this->protocol = isset($_SERVER['HTTP_X_FORWARDED_PROTO']) ? $_SERVER['HTTP_X_FORWARDED_PROTO'] : $_SERVER['REQUEST_SCHEME'];
		$this->hostname = strtolower(current(explode(':', isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'])));
		$this->domain   = strpos($this->hostname, 'www.') === 0 ? substr($this->hostname, 4) : $this->hostname;
		$this->basepath = str_replace('/'.basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
		$this->baseurl  = $this->protocol.'://'.$this->hostname.$this->basepath;
		$this->homepath = $this->basepath.'/';
		$this->urlpath  = rawurldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
		$this->path     = preg_replace('#^'.preg_quote($this->basepath).'#', '', $this->urlpath);
		if($this->urlpath == $_SERVER['SCRIPT_NAME'])
			$this->path = dirname($this->path);
		$this->fullpath = $this->protocol.'://'.$this->hostname.$this->urlpath;
	}
	public function __get($name) {
		if(isset($this->prop[$name])) return $this->prop[$name];
	}
	public function __call($method, $args) {
		if(isset($this->prop[$method]) && is_callable($this->prop[$method])) return $this->prop[$method](...$args);
	}
	public function addProperty($name, $value) {
		$this->prop[$name] = $value;
	}

	// run
	public function run() {
		$this->action('init');
		$this->dispatch();
		$this->action('ready');
		$this->invoke();
	}

	// routing
	public function rule(...$args) {
		$route = array_pop($args); // last args as route
		if(empty($args))
			$this->rules['default'] = $route;
		else
			foreach($args as $rule) $this->rules[$rule] = $route;
	}
	public function route(...$args) {
		$route = array_pop($args); // last args as route
		if(empty($args))
			$this->rules['default'] = $route;
		else
			foreach($args as $rule) $this->rules[$rule] = $route;
	}
	public function part($name, $regex) {
		$this->part["%$name%"] = "(?<$name>$regex)";
	}
	protected function rule2regex($rule) {
		return '#^'.str_replace(array_keys($this->part), array_values($this->part), preg_quote(rtrim($rule, '/'), '#')).'/?$#';
	}
	protected function dispatch() {
		foreach($this->rules as $rule => $route) {
			if(preg_match($this->rule2regex($rule), $this->path, $params)) {
				$params = array_filter($params, 'is_string', ARRAY_FILTER_USE_KEY);
				$this->params->append($params);
				$this->rule = $rule;
				$this->route = $route;
				return;
			}
		}
		$this->rule = 'default';
		$this->route = $this->rules['default'];
	}
	public function invoke($route = null) {
		$route = $route ?: $this->route;
		if(is_callable($route)) {
			call_user_func($route, $this); // $route is a callable/callback
		} elseif(method_exists($this, 'route_'.$route)) {
			$this->{'route_'.$route}();
		} else {
			$route = strval($route);
			$path = is_file($route) ? $route : $this->config->core_path('core')."/$route.php";
			if(is_file($path))
				call_user_func(static function($app) use($path) {
					extract($app->prop);
					include $path;
				}, $this);
		}
	}

	// action manipulator
	public function addAction($hook, $action, $top_priority = false) {
		$this->action[$hook][] = $action;
		if($top_priority)
			array_unshift($this->action[$hook], array_pop($this->action[$hook]));
	}
	public function action($hook) {
		// regular action
		if(!empty($this->action[$hook])) {
			foreach($this->action[$hook] as $action) $this->invoke($action);
		}
		// specific action hook by route is exists
		if(is_string($this->route) && !empty($this->action[$this->route.'_'.$hook])) {
			foreach($this->action[$this->route.'_'.$hook] as $action) $this->invoke($action);
		}
	}
	public function removeAction(...$hooks) {
		foreach($hooks as $hook) unset($this->action[$hook]);
	}
	/* @since: 1.4 */
	public function getRegisteredAction() {
		return array_keys($this->action);
	}

	// filter manipulator
	public function addFilter($hook, $filter) {
		$this->filter[$hook][] = $filter;
	}
	public function filter($hook, $var, ...$args) {
		// regular filter
		if(!empty($this->filter[$hook])) {
			foreach($this->filter[$hook] as $filter)
				$var = is_callable($filter) ? call_user_func($filter, $var, ...$args) : (gettype($filter) == gettype($var) ? $filter : $var);
		}
		// specific filter hook by route is exists
		if(is_string($this->route) && !empty($this->filter[$this->route.'_'.$hook])) {
			foreach($this->filter[$this->route.'_'.$hook] as $filter)
				$var = is_callable($filter) ? call_user_func($filter, $var, ...$args) : (gettype($filter) == gettype($var) ? $filter : $var);
		}
		// specific route matching hook filter is exists
		if(is_string($this->route) && $this->route.'_' == substr($hook, 0, strlen($this->route)+1)) {
			$custom_hook = substr($hook, strlen($this->route)+1);
			if(!empty($this->filter[$custom_hook]))
				foreach($this->filter[$custom_hook] as $filter)
					$var = is_callable($filter) ? call_user_func($filter, $var, ...$args) : (gettype($filter) == gettype($var) ? $filter : $var);
		}
		return $var;
	}
	public function removeFilter(...$hooks) {
		foreach($hooks as $hook) unset($this->filter[$hook]);
	}
	/* @since: 1.4 */
	public function getRegisteredFilter() {
		return array_keys($this->filter);
	}

	// output
	public function render($view) {
		$this->action('render');
		$path = $this->config->theme_path('theme')."/$view.php";
		$path = is_file($path) ? $path : $this->config->theme_path('theme')."/index.php";
		if(is_file($path))
			call_user_func(static function($view, $app) use($path) {
				extract($app->prop);
				include $path;
			}, $view, $this);
	}
	public function JSONResponse($data = [], $message = '', $error_code = null, $error_message = null) {
		$this->action('json');
		if($error_code && !$error_message) {
			switch($error_code) {
				case 400: $error_message = 'Bad request'; break;
				case 401: $error_message = 'Unauthorized'; break;
				case 403: $error_message = 'Forbidden'; break;
				case 404: $error_message = 'Not found'; break;
			}
		}
		$errors = array_filter(['code' => $error_code, 'message' => $error_message]);
		$response = ['success' => empty($errors), 'errors' => $errors, 'message' => $message, 'data' => $data];
		error_reporting(0);
		if($error_code) http_response_code($error_code);
		header('Cache-Control: no-transform,public,max-age=300,s-maxage=900');
		if($this->config->cors)
			header('Access-Control-Allow-Origin: *');
		if(!empty($_GET['callback']) && $this->config->jsonp) {
			header('Content-Type: text/javascript; charset=utf-8');
			exit($_GET['callback'].'('.json_encode($response).');');
		} else {
			header('Content-Type: application/json; charset=utf-8');
			exit(json_encode($response));
		}
	}
	public function parse($string, $data = []) {
		return preg_replace_callback('/{{(.*?)}}/i', function($match) use($data) {
			$replace = '';
			foreach(str_getcsv($match[1]) as $modifier) {
				$keys = array_filter(explode('.', $modifier));
				switch(true) {
					case empty($keys): break;
					case $keys[0] == $modifier && isset($this->{$modifier}):
						$replace = $this->{$modifier}; break;
					case $keys[0] == $modifier && is_callable($modifier):
						$replace = $modifier($replace); break;
					case $keys[0] == $modifier && isset($this->filter[$modifier]):
						$replace = $this->filter($modifier, $replace); break;
					case !$replace && isset($this->prop[$keys[0]]) && isset($keys[1]):
						$replace = $this->prop[$keys[0]]->{$keys[1]}; break;
					case !$replace && $keys[0] == $modifier && isset($data[$keys[0]]):
						$replace = $data[$keys[0]]; break;
					case !$replace && strpos($match[1], '"'.$modifier.'"') !== false:
						$replace = stripslashes($modifier); break;
				}
			}
			return $replace;
		}, $string);
	}

	// utilities
	/* @since: 1.4 */
	public function isAjaxRequest() {
		return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
	}
	public function fetch($method, $url, $fields = null, $options = []) {
		return Utilities::fetch($method, $url, $fields, $options);
	}
	public function createURL($pattern, array $replacer = []) {
		return Utilities::createURL($pattern, $replacer);
	}
	public function slug($str, $options = []) {
		return Utilities::slug($str, $options);
	}
	public function unslug($slug) {
		return Utilities::unslug($slug);
	}
	public function seeded_shuffle(array &$items, $seed = false) {
		Utilities::seeded_shuffle($items, $seed ?: $this->domain);
	}
	public function seeded_unshuffle(array &$items, $seed = false) {
		Utilities::seeded_unshuffle($items, $seed ?: $this->domain);
	}
	public function str_seeded_shuffle($str, $seed = false) {
		return Utilities::str_seeded_shuffle($str, $seed ?: $this->domain);
	}
	public function str_seeded_unshuffle($str, $seed = false) {
		return Utilities::str_seeded_unshuffle($str, $seed ?: $this->domain);
	}
}
