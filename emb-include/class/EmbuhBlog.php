<?php

/**
 * EmbuhBlog Class
 *
 * Simple Blog Engine with SQLite3 Database
 *
 * @package    EmbuhEngine
 * @author     errorisme <errorisme@gmail.com>
 * @copyright  2023 (c) EmbuhTeam
 * @license    Private
 * @require    App.php (v1.4)
 * @require    Collection.php
 * @require    Database.php
 * @require    Auth.php
 * @require    Widget.php
 * @require    Parsedown.php
 * @version    1.0.0
 * @modified   2024-04-16
 */
class EmbuhBlog extends App {
	const VERSION = '1.0.0';

	// Parent method
	public function __construct($config = []) {
		// default config - Priority: Lower
		$default_config = [
			'site_name'           => 'Embuh Blog',
			'site_tagline'        => 'Welcome to my site',
			'site_theme'          => 'fasthink',
			// 'site_https'          => true,
			// 'site_www'            => false,
			'site_item_per_page'  => 10,

			'rule_home'           => '/',
			'rule_home_paged'     => '/page/%pagenum%/',
			'rule_post'           => '/%year%/%month%/%title%.html',
			'rule_page'           => '/p/%title%.html',
			'rule_search'         => '/search/%title%.html',
			'rule_search_paged'   => '/search/%pagenum%/%title%.html',
			'rule_category'       => '/category/%title%',
			'rule_category_paged' => '/category/%title%/page/%pagenum%',
			'rule_tag'            => '/tag/%title%',
			'rule_tag_paged'      => '/tag/%title%/page/%pagenum%',
			'rule_sitemap'        => '/hide/your/sitemap.xml',
			'rule_sitemap_paged'  => '/hide/your/sitemap-%pagenum%.xml',
			'rule_dashboard'      => '/embuh-dashboard',

			'path_include'        => 'emb-include',
			'path_core'           => 'emb-include/core',
			'path_database'       => 'emb-include/database',
			'path_dashboard'      => 'emb-include/dashboard',
			'path_config'         => 'emb-include/config',
			'path_config_domain'  => 'emb-include/config-domain',
			'path_addons'         => 'emb-include/add-ons',
			'path_content'        => 'emb-content',
			'path_themes'         => 'emb-content/themes',

			'active_addons'       => [],
		];

		$this->addProperty('params', new Collection);
		$this->addProperty('config', new Collection(array_merge($default_config, $config)));
		$this->addProperty('content', new Collection);
		$this->addProperty('widget', new Widget);
		$this->addProperty('runtime', new Collection);

		$this->params->GET = new Collection($_GET);
		$this->params->POST = new Collection($_POST);
		$this->params->REQUEST = new Collection($_REQUEST);

		$this->part('id',      '[0-9]+');
		$this->part('year',    '[0-9]{4}');
		$this->part('month',   '[0-9]{2}');
		$this->part('date',    '[0-9]{2}');
		$this->part('pagenum', '[0-9]+');
		$this->part('title',   '[[:^punct:]\-]+');
		$this->part('route',   '[a-zA-Z0-9\-\.]+');
		$this->part('method',  '[a-zA-Z0-9\-\.]+');
		$this->part('target',  '[a-zA-Z0-9\-\.]+');
		$this->part('act',     '[a-zA-Z0-9\-\.]+');

		if(php_sapi_name() == "cli") {
			$this->params->cli_mode = true;
			$this->cli_construct();
		}

		$this->protocol = isset($_SERVER['HTTP_X_FORWARDED_PROTO']) ? $_SERVER['HTTP_X_FORWARDED_PROTO'] : $_SERVER['REQUEST_SCHEME'];
		$this->hostname = strtolower(current(explode(':', isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'])));
		$this->domain   = strpos($this->hostname, 'www.') === 0 ? substr($this->hostname, 4) : $this->hostname;
		$this->basepath = rtrim(str_replace(DIRECTORY_SEPARATOR, '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
		$this->homepath = $this->basepath.'/';
		$this->baseurl  = $this->protocol.'://'.$this->hostname.$this->basepath;
		$this->homeurl  = $this->baseurl.'/';
		$this->path     = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : (isset($_SERVER['REDIRECT_PATH_INFO']) ? $_SERVER['REDIRECT_PATH_INFO'] : '/');
		$this->urlpath  = rawurldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
		$this->fullpath = $this->protocol.'://'.$this->hostname.$this->urlpath;
		// parent::__construct();
		$this->reverse_domain = implode('.', array_reverse(array_filter(explode('.', $this->domain))));
		$this->app_root_path  = APP_ROOT_PATH;

		// LOAD CONFIG
		// Load default config
		$this->invoke("{$this->config->path_config}/default.php");

		// Load single domain config
		foreach(glob("{$this->config->path_config_domain}/{{$this->domain},{$this->reverse_domain}}.php", GLOB_BRACE) as $path)
			$this->invoke($path);

		// LOAD DATABASE
		// load permanent db config
		$this->invoke("{$this->config->path_config}/db-config.php");
		// Initializing sqlite3 database
		$this->config->db_name = $this->config->db_name ?: "{$this->reverse_domain}.sqlite";
		$this->config->db_skel = $this->config->db_skel ?: "Skeleton.sqlite";
		if(!file_exists("{$this->config->path_database}/{$this->config->db_name}"))
			copy("{$this->config->path_database}/{$this->config->db_skel}", "{$this->config->path_database}/{$this->config->db_name}");
		$this->addProperty('db', new Database("sqlite:"."{$this->config->path_database}/{$this->config->db_name}"));
		$this->db->exec("PRAGMA foreign_keys = ON");

		// Load neccessary config from database
		$this->config->append($this->opt_getOptions('site_', 'rule_', 'misc_', 'meta_robots_', 'active_'));

		// LOAD ADDONS
		// foreach(glob("{$this->config->path_addons}/[!_]*/index.php") as $path) {
		// foreach($this->config->active_addons as $addons) {
		// 	$path = "{$this->config->path_addons}/{$addons}/index.php";
		// 	call_user_func(static function($app) use ($path) {
		// 		extract($app->prop);
		// 		$config->append($app->opt_getOptions(basename(dirname($path))."_"));
		// 		include $path;
		// 	}, $this);
		// }
		foreach($this->config->active_addons as $addons)
			$this->invokeAddons($addons);
		$this->routingAvailableRule();
	}
	public function run() {
		if($this->params->cli_mode)
			$this->cli_init();
		$this->action('init');
		$this->dispatch();
		$this->wwwRedirector();
		$this->httpsRedirector();
		// $this->searchQueryRedirector();
		$this->action('ready');
		$this->invoke("{$this->config->path_core}/_init.php");
		// highlight_string(print_r($this, true)); exit;
		$this->invoke($this->route);
		$this->action('finish');
	}
	public function invoke($handler = null) {
		if(!$handler)
			return;
		if(is_callable($handler))
			return call_user_func($handler, $this); // $route is a callable/callback
		// $handler = strval($handler);
		$path    = file_exists("$handler") ? "$handler" : "{$this->config->path_core}/{$handler}.php";
		if(is_file($path))
			return call_user_func(static function($app) use($path) {
				extract($app->prop);
				include $path;
			}, $this);
	}
	public function render($view) {
		$this->action('render');
		$this->invoke("{$this->config->path_themes}/{$this->config->site_theme}/theme-init.php");
		$path = "{$this->config->path_themes}/{$this->config->site_theme}/$view.php";
		$path = is_file($path) ? $path : "{$this->config->path_themes}/{$this->config->site_theme}/index.php";
		if(is_file($path))
			call_user_func(static function($app) use($view, $path) {
				extract($app->prop);
				include $path;
			}, $this);
	}
	protected function invokeAddons($addons) {
		if($this->runtime->{"addons_invoked_$addons"}) return;
		$path = "{$this->config->path_addons}/{$addons}/index.php";
		call_user_func(static function($app) use ($path, $addons) {
			extract($app->prop);
			$config->append($app->opt_getOptions("{$addons}_"));
			include $path;
		}, $this);
		$this->runtime->{"addons_invoked_$addons"} = true;
	}
	protected function routingAvailableRule() {
		// route general rule to core
		foreach($this->config->get('rule_') as $key => $value) {
			$router = preg_replace(['/^rule_/', '/_paged$/'], '', $key);
			$this->route($value, $router);
		}
		// route dashboard
		$this->rule($this->config->rule_dashboard.'/%route%', 'dashboard');
		$this->rule($this->config->rule_dashboard.'/%route%/%method%', 'dashboard');
		$this->rule($this->config->rule_dashboard.'/%route%/%method%/%target%', 'dashboard');
		$this->rule($this->config->rule_dashboard.'/%route%/%method%/%target%/%act%', 'dashboard');
		// route addons rule
		foreach($this->config->active_addons as $addons) {
			foreach($this->config->get("{$addons}_rule_") as $key => $value) {
				$router = preg_replace(["/^{$addons}_rule_/", '/_paged$/'], '', $key);
				// list(, , $router) = explode('_', $key, 3);
				$this->rule($value, "{$this->config->path_addons}/{$addons}/route_{$router}.php");
			}
		}
	}

	// CLI utilities
	protected function cli_construct() {
		// if(!$this->params->cli_mode) return;
		$this->rule('/cli', 'cli');
		$this->params->cli_argv = [];
		// extract argv into regular arguments format
		foreach (array_slice($_SERVER['argv'], 1) as $arg) {
			if (preg_match('/^-([a-z]+)$/i', $arg, $matches))
				foreach (str_split($matches[1]) as $option)
					$this->params->cli_argv[] = "-$option";
			elseif (preg_match('/^(-[a-z]{1})(\d+)$/i', $arg, $matches))
					$this->params->cli_argv = array_merge([$matches[1], $matches[2]], $this->params->cli_argv);
			// elseif (strpos($arg, '=') !== false)
			elseif (preg_match('/^(-[a-z]{1}|--[a-zA-Z0-9\-_]{2,})\=/i', $arg, $matches))
				$this->params->cli_argv = array_merge(explode('=', $arg, 2), $this->params->cli_argv);
			else
				$this->params->cli_argv[] = $arg;
		}
		$this->config->active_addons = array_merge($this->config->active_addons, $this->cli_argvGetValues(null, 'add-ons'));
		// print_r($this->cli_argvGetValues(null, 'add-ons')); exit;
		$this->params->cli_quiet     = $this->cli_argvGetOption('q', 'silent');
		$this->params->cli_verbose   = $this->cli_argvGetOption(null, 'verbose');
		$this->params->cli_action    = $this->cli_argvGetValue(null, 'act|action|command');
		// prepare required $_SERVER entries
		$document_root = realpath($this->cli_argvGetValue(null, 'root') ?: getcwd());
		$base = $this->cli_argvGetValue(null, 'base') ?: preg_replace("#^{$document_root}#", "", getcwd());
		$host = $this->cli_argvGetValue(null, 'domain') ?: $this->cli_argvGetValue(null, 'host') ?: 'localhost';
		$path = $this->cli_argvGetValue(null, 'path') ?: '/cli';
		$url = $this->cli_argvGetValue('u', 'url');
		$scheme = 'https';
		if($url) {
			filter_var($url, FILTER_VALIDATE_URL) OR $this->cli_buffer("EmbuhBlog PHP-CLI: Error: Invalid URL {$url}", false, true);
			extract(parse_url($url));
		}
		$_SERVER['REQUEST_SCHEME'] = $scheme;
		$_SERVER['HTTP_HOST']      = $host;
		$_SERVER['PATH_INFO']      = $path;
		$_SERVER['REQUEST_URI']    = $base.$path;
		$_SERVER['DOCUMENT_ROOT']  = $document_root;
		$_SERVER['SCRIPT_NAME']    = "{$base}/index.php";
		$_SERVER['PHP_SELF']       = "{$base}/index.php";
	}
	protected function cli_init() {
		// if(!$this->params->cli_mode) return;
		if($this->cli_argvGetOption(null, 'clean')) {
		   $this->filter = [];
		   $this->action = [];
		}
		$this->params->cli_addons = $this->cli_argvGetValues('l', 'load-file');
		foreach($this->params->cli_addons as $path) {
			if(!file_exists($path)) {
				$this->cli_buffer("Notice: $path is not valid file, option ignored");
				continue;
			}
			$this->invoke($path);
		}
		// foreach($this->cli_argvGetValues(null, '--add-ons') as $addons)
		// 	$this->invokeAddons($addons);
		$this->action('cli_init');
	}
	public function cli_argvShift($short, $long = null, $fetch_value = false, $multi_value = false, $opt_as_value = false) {
		$value = $fetch_value ? ($multi_value ? [] : null) : false;
		$positional = [];
		while (count($this->params->cli_argv)) {
			$opt = array_shift($this->params->cli_argv);
			if (($short && preg_match("/^-({$short})$/", $opt, $match)) || ($long && preg_match("/^--({$long})$/", $opt, $match))) {
				if ($fetch_value) {
					if ($multi_value) {
						$value[] = array_shift($this->params->cli_argv);
					} else {
						$value = array_shift($this->params->cli_argv);
					}
				} else {
					$value = $opt_as_value ? $match[1] : true;
				}
			} else {
				$positional[] = $opt;
			}
		}
		$this->params->cli_argv = $positional;
		return $value;
	}
	public function cli_argvGetOption($short, $long = null, $opt_as_value = false) {
		return $this->cli_argvShift($short, $long, false, false, $opt_as_value);
	}
	public function cli_argvGetValue($short, $long = null) {
		return $this->cli_argvShift($short, $long, true);
	}
	public function cli_argvGetValues($short, $long = null) {
		return $this->cli_argvShift($short, $long, true, true);
	}
	public function cli_buffer($message, $cr = false, $exit = false) {
		if(!$this->params->cli_quiet)
			echo $cr ? "$message\r" : "$message\n";
		if($exit) exit;
	}

	// Preprocessing
	protected function wwwRedirector() {
		if($this->params->cli_mode) return;
		if($this->config->www) {
			if(stripos($_SERVER['HTTP_HOST'], 'www.') !== 0) {
				header('HTTP/1.1 301 Moved Permanently');
				header('X-Robots-Tag: noindex');
				header('Location: '.$this->protocol.'://www.'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
				exit;
			}
		} else {
			if(stripos($_SERVER['HTTP_HOST'], 'www.') === 0) {
				header('HTTP/1.1 301 Moved Permanently');
				header('X-Robots-Tag: noindex');
				header('Location: '.$this->protocol.'://'.str_ireplace('www.', '', $_SERVER['HTTP_HOST']).$_SERVER['REQUEST_URI']);
				exit;
			}
		}
	}
	protected function httpsRedirector() {
		if($this->params->cli_mode) return;
		if($this->config->site_https && $this->protocol == 'http') {
			header('HTTP/1.1 301 Moved Permanently');
			// header('X-Robots-Tag: noindex');
			header('Location: https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
			exit;
		}
	}
	protected function searchQueryRedirector() {
		if($this->params->cli_mode) return;
		if(empty($_GET['q'])) return;
		$slug = $this->slug($_GET['q']);
		if(!$slug) return;
		header('HTTP/1.1 301 Moved Permanently');
		header('X-Robots-Tag: noindex');
		header('Location: '.$this->createPermalink('search', ['title' => $slug]));
		exit;
	}

	// Anggep wae Model
	public function opt_getOptions(...$prefixes) {
		if(empty($prefixes)) {
			$rows = $this->db->rows("SELECT * FROM embuh_option WHERE SUBSTR(key, 1, 1) != '_'");
		} else {
			$placeholders = implode(' OR key LIKE ', array_fill(0, count($prefixes), '?'));
			$query = "SELECT * FROM embuh_option WHERE key LIKE $placeholders";
			$query_params = array_map(function ($prefix) { return $prefix.'%'; }, $prefixes);
			$rows = $this->db->rows($query, $query_params);
		}
		$options = [];
		foreach($rows as $row)
			$options[$row['key']] = $this->fixTypeGet($row['value']);
		return $options;
	}
	public function opt_getOption($key) {
		$row = $this->db->row("SELECT * FROM embuh_option WHERE key=?", [$key]);
		if(empty($row)) return null;
		return $this->fixTypeGet($row['value']);
	}
	public function opt_setOption($key, $value) {
		return $this->db->run("INSERT OR REPLACE INTO embuh_option (key, value) VALUES (?,?)", [$key, $this->fixTypeSet($value)])->rowCount();
	}
	public function opt_deleteOption($key) {
		return $this->db->run("DELETE FROM embuh_option WHERE key=?", [$key])->rowCount();
	}

	public function meta_setMeta($post_id, $key, $value) {
		return $this->db->run("INSERT OR REPLACE INTO embuh_post_meta (post_id, key, value) VALUES (?,?,?)", [(int)$post_id, $key, $this->fixTypeSet($value)])->rowCount();
	}
	public function meta_getMeta($post_id, $key = null) {
		if($key)
			return $this->fixTypeGet($this->db->col("SELECT value FROM embuh_post_meta WHERE post_id=? AND key=?", [(int)$post_id, $key]));
		$meta = [];
		$rows = $this->db->rows("SELECT key, value FROM embuh_post_meta WHERE post_id=?", [(int)$post_id]);
		foreach($rows as $row)
			$meta[$row['key']] = $this->fixTypeGet($row['value']);
		return $meta;
	}
	public function meta_getPostId($key, $value) {
		return $this->db->col("SELECT post_id FROM embuh_post_meta WHERE key=? AND value=?", [$key, $value]);
	}
	public function meta_deleteMeta($post_id, $key = null) {
		return $key
			? $this->db->run("DELETE FROM embuh_post_meta WHERE post_id=? AND key=?", [(int)$post_id, $key])->rowCount()
			: $this->db->run("DELETE FROM embuh_post_meta WHERE post_id=?", [(int)$post_id])->rowCount();
	}

	public function user_getUserByUsername($username) {
		return $this->db->row("SELECT * FROM embuh_user WHERE username=?", [$username]);
	}
	public function user_getUserById($id) {
		return $this->db->row("SELECT * FROM embuh_user WHERE id=?", [(int)$id]);
	}
	public function user_getUserByEmail($email) {
		return $this->db->row("SELECT * FROM embuh_user WHERE email=?", [$email]);
	}
	public function user_createUser($username, $password, $name, $email) {
		return $this->db->run(
			"INSERT INTO embuh_user (username, password, name, email, date_registered) VALUES (?, ?, ?, ?, STRFTIME('%s'))",
			[$username, password_hash($password, PASSWORD_DEFAULT), $name, $email]
		)->rowCount();
	}
	public function user_updatePassword($id, $password) {
		return $this->db->run("UPDATE embuh_user SET password=? WHERE id=?", [password_hash($password, PASSWORD_DEFAULT), $id])->rowCount();
	}

	public function post_createPost($author_id, $title, $slug, $content, $summary, $type, $published, $date_published) {
		return $this->db->run(
			"INSERT INTO embuh_post (author_id, title, slug, content, summary, type, published, date_published, date_created) VALUES (?,?,?,?,?,?,?,?, STRFTIME('%s'))",
			[(int)$author_id, $title, $slug, $content, $summary, $type, (int)$published, $date_published]
		)->rowCount();
	}
	public function post_updatePost($post_id, $title, $slug, $content, $summary, $published, $date_published, $date_modified) {
		return $this->db->run(
			"UPDATE embuh_post SET title=?, slug=?, content=?, summary=?, published=?, date_published=?, date_modified=? WHERE id=?",
			[$title, $slug, $content, $summary, (int)$published, $date_published, $date_modified, (int)$post_id]
		)->rowCount();
	}
	public function post_insertOrUpdatePost($author_id, $title, $slug, $content, $summary, $type, $published, $date_published) {
		$post = $this->post_getPostBySlug($slug);
		if(empty($post))
			return $this->post_createPost($author_id, $title, $slug, $content, $summary, $type, $published, $date_published);
		return $this->post_updatePost($post['post_id'], $title, $post['slug'], $content, $summary, $published, $post['date_published'] ?: $published, time());
	}
	public function post_createPostFromArray($new, &$message = null) {
		if(empty($new))
			return !($message = 'Post data is empty');
		$new['author_id']      = !empty($new['author_id']) ? $new['author_id'] : 1;
		$new['title']          = !empty($new['title']) ? $new['title'] : 'Untitled';
		$new['slug']           = !empty($new['slug']) ? ($this->isValidSlug($new['slug']) ? $new['slug'] : $this->slug($new['slug'])) : $this->slug($new['title']);
		$new['content']        = !empty($new['content']) ? $new['content'] : '';
		$new['summary']        = !empty($new['summary']) ? $new['summary'] : '';
		$new['type']           = !empty($new['type']) ? $new['type'] : 'post';
		$new['published']      = isset($new['published']) ? $new['published'] : 1;
		$new['date_published'] = !empty($new['date_published']) ? $new['date_published'] : ($new['published'] ? time() : null);
		$new['categories']     = !empty($new['categories']) ? (is_array($new['categories']) ? implode(',', $new['categories']) : $new['categories']) : '';
		$new['tags']           = !empty($new['tags']) ? (is_array($new['tags']) ? implode(',', $new['tags']) : $new['tags']) : '';
		$new['meta']           = !empty($new['meta']) ? $new['meta'] : [];
		foreach($new as $key => $value)
			if(strpos($key, 'meta_') === 0)
				$new['meta'][substr($key, 5)] = $value;
		if($post_id = $this->post_getPostIdBySlug($new['slug']))
			return !($message = 'Duplicate slug detected');
		$result = $this->post_createPost($new['author_id'], $new['title'], $new['slug'], $new['content'], $new['summary'], $new['type'], $new['published'], $new['date_published']);
		if(!$result)
			return !($message = 'Create post failed');
		$post_id = $this->db->pdo->lastInsertId();
		if(!empty($new['categories'])) $this->post_updateCategories($post_id, $new['categories']);
		if(!empty($new['tags']))       $this->post_updateTags($post_id, $new['tags']);
		if(!empty($new['meta']))       $this->post_updateMeta($post_id, $new['meta']);
		return $post_id;
	}
	public function post_updatePostFromArray($post_id, $post_data, &$message = null) {
		if(empty($post_data))
			return !($message = 'Update post data is empty');
		$old_post = $this->post_getPostById($post_id);
		if(empty($old_post))
			return !($message = 'Post not found '.$post_id);
		$old_post['tags']       = implode(', ', array_column($old_post['tags'], 'title'));
		$old_post['categories'] = implode(', ', array_column($old_post['categories'], 'title'));
		foreach($old_post as $key => $value) $to_update[$key] = $value;

		$post_data['published'] = isset($post_data['published']) ? $post_data['published'] : $old_post['published'];
		$post_data['slug']      = !empty($post_data['slug']) ? ($this->isValidSlug($post_data['slug']) ? $post_data['slug'] : $this->slug($post_data['slug'])) : $old_post['slug'];

		$isNeedToUpdate = $this->isModified($old_post, $post_data);
		if(!$isNeedToUpdate)
			return !($message = 'Nothing changed');
		foreach($post_data as $key => $value) {
			if(strpos($key, 'meta_') === 0)
				$to_update['meta'][substr($key, 5)] = $value;
			else if(array_key_exists($key, $to_update))
				$to_update[$key] = $value;
		}
		$to_update['tags']       = !empty($post_data['tags']) ? (is_array($post_data['tags']) ? implode(',', $post_data['tags']) : $post_data['tags']) : '';
		$to_update['categories'] = !empty($post_data['categories']) ? (is_array($post_data['categories']) ? implode(',', $post_data['categories']) : $post_data['categories']) : '';
		$to_update['meta']       = !empty($to_update['meta']) ? $to_update['meta'] : [];
		$this->post_updateTags($post_id, $to_update['tags']);
		$this->post_updateCategories($post_id, $to_update['categories']);
		$this->post_updateMeta($post_id, $to_update['meta']);
		if($this->post_isDuplicateSlug($post_data['slug'], $post_id))
			return !($message = 'Duplicate slug detected');
		if($to_update['published'] == 1 && $old_post['date_published'] === null)
			$to_update['date_published'] = time();
		if(empty($to_update['summary']))
			$to_update['summary'] = null;
		$modified = $this->isModified(array_filter($old_post, function($key) { return in_array($key, ['title', 'content', 'summary']); }, ARRAY_FILTER_USE_KEY), $to_update);
		$to_update['date_modified'] = $modified ? time() : $old_post['date_modified'];
		$post_id = $this->post_updatePost($post_id, $to_update['title'], $to_update['slug'], $to_update['content'], $to_update['summary'], $to_update['published'], $to_update['date_published'], $to_update['date_modified']);
		if(!$post_id)
			return !($message = 'Nothing updated');
		return $post_id;
	}
	public function post_draftPost($post_id) {
		return $this->db->run("UPDATE embuh_post SET published = 0 WHERE id=?", [(int)$post_id])->rowCount();
	}
	public function post_publishPost($post_id) {
		return $this->db->run("UPDATE embuh_post SET published = 1, date_published = COALESCE(date_published, STRFTIME('%s')) WHERE id=?", [(int)$post_id])->rowCount();
	}
	public function post_publishPostNow($post_id) {
		return $this->db->run("UPDATE embuh_post SET published = 1, date_published = STRFTIME('%s') WHERE id=?", [(int)$post_id])->rowCount();
	}
	public function post_trashPost($post_id) {
		return $this->db->run("UPDATE embuh_post SET published = -1 WHERE id=?", [(int)$post_id])->rowCount();
	}
	public function post_deletePost($post_id) {
		return $this->db->run("DELETE FROM embuh_post WHERE id=?", [(int)$post_id])->rowCount();
	}

	public function post_getPostById($post_id, $post_params = []) {
		return $this->post_completeData($this->post_getPostRawById($post_id, $post_params));
	}
	public function post_getPostBySlug($slug, $post_params = []) {
		return $this->post_completeData($this->post_getPostRawBySlug($slug, $post_params));
	}
	public function post_getPostRawById($post_id, $post_params = []) {
		$post_params = array_merge(['post_id' => $post_id], $post_params);
		$posts = $this->post_getPosts($post_params, 1);
		return !empty($posts) ? $posts[0] : [];
	}
	public function post_getPostRawBySlug($slug, $post_params = []) {
		$post_params = array_merge(['slug' => $slug], $post_params);
		$posts = $this->post_getPosts($post_params, 1);
		return !empty($posts) ? $posts[0] : [];
	}
	public function post_getPostIdBySlug($slug) {
		return (int)$this->db->col("SELECT id FROM embuh_post WHERE slug=?", [$slug]);
	}

	public function post_getPosts($post_params = [], $limit = -1, $offset = -1) {
		return array_map([$this, 'post_completeData'], $this->post_getPostsRaw($post_params, $limit, $offset));
	}
	public function post_getPostsByAuthorId($author_id, $limit = -1, $offset = -1) {
		return $this->post_getPosts(['author_id' => (int)$author_id], $limit, $offset);
	}

	public function post_getPostsRaw($post_params = [], $limit = -1, $offset = -1) {
		extract($this->post_getQuery($post_params, $limit, $offset));
		return $this->db->rows($query, $query_params);
	}
	const POST_FIELDS = <<<EOF
      post.id post_id, post.title, post.slug, post.type,
      CASE
        WHEN (post.published = 1 AND post.date_published > STRFTIME('%s')) THEN 'Scheduled'
        WHEN (post.published = 1 AND post.date_published <= STRFTIME('%s')) THEN 'Published'
        WHEN post.published = 0 THEN 'Draft'
        WHEN post.published = -1 THEN 'Trash'
        ELSE 'Draft'
      END status,
      post.date_published, post.date_modified, post.date_created,
      datetime(post.date_published, 'unixepoch') || 'Z' publishedAt,
      datetime(post.date_modified, 'unixepoch') || 'Z' modifiedAt,
      datetime(post.date_created, 'unixepoch') || 'Z' createdAt,
      post.published, post.author_id, user.name author_name, post.content, post.summary
    EOF;
	public function post_getQuery($post_params = [], $limit = -1, $offset = -1) {
		$post_params['fields'] = !empty($post_params['fields']) ? $post_params['fields'] : self::POST_FIELDS;
		$query_params = [];
		$query = <<<EOF
        SELECT
          {$post_params['fields']}
        FROM embuh_post post
        LEFT JOIN embuh_user user ON post.author_id=user.id
        WHERE 1\n
        EOF;
		if(!empty($post_params['post_id'])) {
			$query .= "  AND post.id=?\n";
			$query_params[] = (int)$post_params['post_id'];
		}
		if(!empty($post_params['post_ids'])) {
			$post_ids = implode(',', array_filter(array_map('intval', is_array($post_params['post_ids']) ? $post_params['post_ids'] : explode(',', $post_params['post_ids']))));
			$query .= "  AND post.id IN ($post_ids)\n";
		}
		if(!empty($post_params['author_id'])) {
			$query .= "  AND post.author_id=?\n";
			$query_params[] = (int)$post_params['author_id'];
		}
		if(!empty($post_params['keywords'])) {
			foreach(array_filter(explode(' ', $post_params['keywords'])) as $keyword) {
				if(substr($keyword, 0, 1) == '-') {
					$keyword = substr($keyword, 1);
					$query .= "  AND post.title || ' ' || post.content NOT LIKE ?\n";
					$query_params[] = "%$keyword%";
				} else {
					$query .= "  AND post.title || ' ' || post.content LIKE ?\n";
					$query_params[] = "%$keyword%";
				}
			}
		}
		if(!empty($post_params['keyword'])) {
			$keyword = $post_params['keyword'];
			if(substr($keyword, 0, 1) == '-') {
				$keyword = substr($keyword, 1);
				$query .= "  AND post.title || ' ' || post.content NOT LIKE ?\n";
				$query_params[] = "%$keyword%";
			} else {
				$query .= "  AND post.title || ' ' || post.content LIKE ?\n";
				$query_params[] = "%$keyword%";
			}
		}
		if(!empty($post_params['category_id'])) {
			$query = str_replace("WHERE 1", "INNER JOIN embuh_post_category AS pc ON post.id=pc.post_id\nWHERE 1", $query);
			$query .= "  AND pc.category_id=?\n";
			$query_params[] = (int)$post_params['category_id'];
		}
		elseif(!empty($post_params['category_slug'])) {
			$query = str_replace("WHERE 1", "INNER JOIN embuh_post_category AS pc ON post.id=pc.post_id INNER JOIN embuh_category AS category ON pc.category_id=category.id\nWHERE 1", $query);
			$query .= "  AND category.slug=?\n";
			$query_params[] = $post_params['category_slug'];
		}
		elseif(!empty($post_params['is_uncategorized'])) {
			$query = str_replace("WHERE 1", "LEFT JOIN embuh_post_category AS pc ON post.id=pc.post_id\nWHERE 1", $query);
			$query .= "  AND pc.post_id IS NULL\n";
		}
		if(!empty($post_params['tag_id'])) {
			$query = str_replace("WHERE 1", "INNER JOIN embuh_post_tag AS pt ON post.id=pt.post_id\nWHERE 1", $query);
			$query .= "  AND pt.tag_id=?\n";
			$query_params[] = (int)$post_params['tag_id'];
		}
		if(!empty($post_params['slug'])) {
			$query .= "  AND post.slug=?\n";
			$query_params[] = $post_params['slug'];
		}
		if(!empty($post_params['type'])) {
			$query .= "  AND post.type=?\n";
			$query_params[] = $post_params['type'];
		}
		if(!empty($post_params['is_scheduled'])) {
			$query .= "  AND (post.published = 1 AND post.date_published > STRFTIME('%s'))\n";
		}
		if(!empty($post_params['is_published'])) {
			$query .= "  AND (post.published = 1 AND post.date_published <= STRFTIME('%s'))\n";
		}
		if(!empty($post_params['!is_published'])) {
			$query .= "  AND post.published < 1\n";
		}
		if(!empty($post_params['is_draft'])) {
			$query .= "  AND post.published = 0\n";
		}
		if(!empty($post_params['is_trash'])) {
			$query .= "  AND post.published = -1\n";
		}
		if(!empty($post_params['!is_trash'])) {
			$query .= "  AND post.published != -1\n";
		}
		if(!empty($post_params['where'])) {
			$query .= "  AND {$post_params['where']}\n";
		}
		$asc_desc = !empty($post_params['sort_desc']) ? 'DESC' : (!empty($post_params['sort_asc']) ? 'ASC' : '');
		if(!empty($post_params['order_by']))
			$query .= "ORDER BY {$post_params['order_by']}\n";
		elseif(!empty($post_params['sort_field']))
			$query .= "ORDER BY {$post_params['sort_field']} {$asc_desc}\n";
		else
			$query .= $asc_desc
			? "ORDER BY COALESCE(post.date_published, post.date_modified, post.date_created) {$asc_desc}\n"
			: "ORDER BY COALESCE(post.date_published, post.date_modified, post.date_created) DESC\n";
		$query .= $limit !== -1 ?  "LIMIT {$limit}\n" : "";
		$query .= $offset !== -1 ? "OFFSET {$offset}\n" : "";
		// highlight_string(print_r(['params' => $post_params, 'query' => $query, 'query_params' => $query_params], true));
		return ['query' => $query, 'query_params' => $query_params];
	}

	public function post_isDuplicateSlug($slug, $post_id = 0) {
		return (bool)$this->db->col("SELECT COUNT(*) FROM embuh_post WHERE slug=? AND id!=?", [$slug, (int)$post_id]);
	}
	public function post_getValidSlug($slug, $post_id = 0) {
		$valid_slug = $slug;
		$duplicate = $this->post_isDuplicateSlug($slug, $post_id);
		$slug_counter = 1;
		while($duplicate) {
			$valid_slug = $this->slug($slug, ['limit' => 191-strlen("-$slug_counter")])."-$slug_counter";
			$duplicate = $this->db->col("SELECT COUNT(*) FROM embuh_post WHERE slug=? AND id!=?", [$valid_slug, (int)$post_id]);
			$slug_counter++;
		}
		return $valid_slug;
	}
	public function post_completeData($post) {
		if(empty($post)) return $post;
		$post = $this->filter('post_complete_data_before', $post);
		$post['categories'] = $this->cat_getCategoriesByPostId($post['post_id']) ?: [['title' => 'Uncategorized', 'slug' => 'uncategorized']];
		foreach($post['categories'] as &$cat)
			$cat['permalink'] = $this->cat_createPermalink($cat);
		$post['tags'] = $this->tag_getTagsByPostId($post['post_id']);
		foreach($post['tags'] as &$tag)
			$tag['permalink'] = $this->tag_createPermalink($tag);
		$post['meta'] = $this->meta_getMeta($post['post_id']);
		foreach($post['meta'] as $key => $value)
			$post["meta_$key"] = $value;
		unset($post['meta']);
		$post['permalink'] = $this->post_createPermalink($post);
		$post = $this->filter('post_complete_data_after', $post);
		return $post;
	}

	public function post_insertTags($post_id, $str_tags) {
		foreach(array_filter(array_map('trim', explode(',', strtolower($str_tags)))) as $value)
			$tags[$this->slug($value)] = $value;
		if(empty($tags))
			return [];

		$this->tag_createTags($tags);
		$query = "SELECT id FROM embuh_tag WHERE slug IN (".implode(',', array_fill(0, count($tags), '?')).")";
		$rows = $this->db->rows($query, array_keys($tags));
		$tag_ids = array_column($rows, 'id');
		if(empty($tag_ids))
			return $tag_ids;
		// insert relationship
		$query = "INSERT OR IGNORE INTO embuh_post_tag (post_id, tag_id) VALUES";
		foreach($tag_ids as $value) $query .= " (?,?),";
		$query = trim($query, ',');
		$query_params = array_reduce($tag_ids, function ($carry, $item) use ($post_id) { return array_merge($carry, [$post_id, $item]); }, []);
		$result = $this->db->run($query, $query_params)->rowCount();
		return $tag_ids;
		// return ['result' => $result, 'q' => $query, 'p' => $query_params, $tags];
	}
	public function post_updateTags($post_id, $str_tags) {
		$rows = $this->db->rows("SELECT tag_id FROM embuh_post_tag WHERE post_id=?", [$post_id]);
		$existing_tag_ids = array_column($rows, 'tag_id');
		$new_tag_ids = $this->post_insertTags($post_id, $str_tags);
		$tag_ids_to_delete = array_diff($existing_tag_ids, $new_tag_ids);
		// delete relationship
		foreach($tag_ids_to_delete as $tag_id)
			$this->db->run("DELETE FROM embuh_post_tag WHERE post_id=? AND tag_id=?", [$post_id, $tag_id]);
		// remove tags that doesnt have relationship
		$deleted = $this->db->exec("DELETE FROM embuh_tag WHERE NOT EXISTS (SELECT 1 FROM embuh_post_tag WHERE embuh_post_tag.tag_id = embuh_tag.id)");
		return $new_tag_ids;
		return ['$existing_tag_ids' => $existing_tag_ids, '$new_tag_ids' => $new_tag_ids, '$tag_ids_to_delete' => $tag_ids_to_delete, '$deleted' => $deleted];
	}

	public function post_createCategories($post_id, $str_categories) {
		foreach(array_filter(array_map('trim', explode(',', $str_categories))) as $value)
			$categories[$this->slug($value)] = $value;
		if(empty($categories))
			return [];
		$this->cat_createCategories($categories);
		$query = "SELECT id FROM embuh_category WHERE slug IN (".implode(',', array_fill(0, count($categories), '?')).")";
		$rows = $this->db->rows($query, array_keys($categories));
		$category_ids = array_column($rows, 'id');
		if(empty($category_ids))
			return $category_ids;
		$query = "INSERT OR IGNORE INTO embuh_post_category (post_id, category_id) VALUES";
		foreach($category_ids as $value) $query .= " (?,?),";
		$query = trim($query, ',');
		$query_params = array_reduce($category_ids, function ($carry, $item) use ($post_id) { return array_merge($carry, [$post_id, $item]); }, []);
		$result = $this->db->run($query, $query_params)->rowCount();
		return $category_ids;
		// return ['result' => $result, 'q' => $query, 'p' => $query_params, $categories];
	}
	public function post_updateCategories($post_id, $str_categories) {
		$rows = $this->db->rows("SELECT category_id FROM embuh_post_category WHERE post_id=?", [$post_id]);
		$existing_category_ids = array_column($rows, 'category_id');
		$new_category_ids = $this->post_createCategories($post_id, $str_categories);
		$category_ids_to_delete = array_diff($existing_category_ids, $new_category_ids);
		foreach($category_ids_to_delete as $category_id)
			$this->db->run("DELETE FROM embuh_post_category WHERE post_id=? AND category_id=?", [$post_id, $category_id]);
		return ['$existing_category_ids' => $existing_category_ids, '$new_category_ids' => $new_category_ids, '$category_ids_to_delete' => $category_ids_to_delete];
	}

	public function post_updateMeta($post_id, $metas) {
		foreach($metas as $key => $value) $this->meta_setMeta($post_id, $key, $value);
	}
	public function post_getPostCount($filter = null, $type = 'post') {
		switch($filter) {
			case 'is_published':
			case '!is_published':
			case 'is_scheduled':
			case 'is_draft':
			case 'is_trash':
			case '!is_trash':
				$row = $this->post_getPostsRaw(['fields' => 'COUNT(*) posts_count', 'type' => $type, $filter => true]);
				break;
			default:
				$row = $this->post_getPostsRaw(['fields' => 'COUNT(*) posts_count', 'type' => $type]);
		}
		return $row[0]['posts_count'];
	}

	public function tag_getTagsByPostId($post_id) {
		return $this->db->rows("SELECT et.* FROM embuh_tag AS et JOIN embuh_post_tag AS ept ON et.id = ept.tag_id WHERE ept.post_id=?", [$post_id]);
	}
	public function tag_createTags($tags) {
		$tags = array_filter(array_map('trim', array_map('strtolower', $tags)));
		if(empty($tags))
			return $tags;
		$query = "INSERT INTO embuh_tag (title, slug)";
		$query_params = [];
		foreach($tags as $title) {
			$query .= " SELECT ?,? WHERE NOT EXISTS (SELECT 1 FROM embuh_tag WHERE slug=?) UNION";
			$query_params[] = $title;
			$query_params[] = $query_params[] = $this->slug($title);
		}
		$query = preg_replace('/ UNION$/', '', $query);
		$this->db->run($query, $query_params);
		return $tags;
	}
	public function tag_getTagBySlug($slug) {
		return $this->db->row("SELECT * FROM embuh_tag WHERE slug=?", [$slug]);
	}
	public function tag_getTagById($id) {
		return $this->db->row("SELECT * FROM embuh_tag WHERE id=?", [(int)$id]);
	}

	public function cat_createCategories($categories) {
		// trim categories and skip uncategorized or empty value
		$categories = array_filter(array_map('trim', $categories), function($category) {
			return !empty($category) && strtolower($category) != 'uncategorized';
		});
		if(empty($categories))
			return $categories;
		$query = "INSERT INTO embuh_category (title, slug)";
		$query_params = [];
		foreach($categories as $title) {
			$slug = $this->slug($title);
			$query .= " SELECT ?,? WHERE NOT EXISTS (SELECT 1 FROM embuh_category WHERE slug=?) UNION";
			$query_params[] = $title;
			$query_params[] = $query_params[] = $slug;
		}
		$query = preg_replace('/ UNION$/', '', $query);
		$this->db->run($query, $query_params);
		return $categories;
	}
	public function cat_getCategoriesByPostId($post_id) {
		return $this->db->rows("SELECT ec.* FROM embuh_category AS ec JOIN embuh_post_category AS epc ON ec.id = epc.category_id WHERE epc.post_id=?", [$post_id]);
	}
	public function cat_getCategoryBySlug($slug) {
		return $this->db->row("SELECT * FROM embuh_category WHERE slug=?", [$slug]);
	}
	public function cat_getCategoryById($id) {
		return $this->db->row("SELECT * FROM embuh_category WHERE id=?", [(int)$id]);
	}

	// Helper
	public function post_createPermalink($post) {
		return $this->createURL($this->protocol.'://'.$this->domain.$this->basepath.$this->config->rule_post, [
			'id'    => $post['post_id'],
			'title' => $post['slug'],
			'year'  => substr($post['publishedAt'], 0, 4),
			'month' => substr($post['publishedAt'], 5, 2),
			'date'  => substr($post['publishedAt'], 8, 2),
		]);
	}
	public function tag_createPermalink($tag) {
		return $this->createURL($this->protocol.'://'.$this->domain.$this->basepath.$this->config->rule_tag, [
			'title' => $tag['slug'],
		]);
	}
	public function cat_createPermalink($cat) {
		return $this->createURL($this->protocol.'://'.$this->domain.$this->basepath.$this->config->rule_category, [
			'title' => $cat['slug'],
		]);
	}
	public static function fixTypeGet($value) {
		$parsed_value = json_decode($value, true);
		if (json_last_error() === JSON_ERROR_NONE)
			return $parsed_value;
		return $value;
	}
	public static function fixTypeSet($value) {
		switch(gettype($value)) {
			case 'boolean':
			case 'integer':
			case 'double' :
			case 'float'  :
			case 'array'  : return json_encode($value);
			case 'string' :
			default       : return strval($value);
		}
	}
	public static function helper_getRelativePath($dir) {
		return Utilities::getRelativePath(realpath(APP_ROOT_PATH), $dir);
	}

	// General Utilities
	public function slug($str, $option = ['limit' => 191]) {
		return Utilities::slug($str, $option);
	}
	public function isValidSlug($slug) {
		return $slug === $this->slug($slug, ['lowercase' => false, 'limit' => 191]);
	}
	public function isModified($old, $new) {
		foreach($new as $key => $value)
			if(array_key_exists($key, $old))
				if($old[$key] != $value)
					return true;
		return false;
	}
	public function createPermalink($rule, array $replacer = []) {
		return Utilities::createURL($this->protocol.'://'.$this->hostname.$this->basepath.$this->config->{'rule_'.$rule}, $replacer);
	}

	// Widget
	function widget_postsToItems($posts, $callback = null) {
		return array_map($callback ?: function($post) {
			return ['text' => $post['title'], 'href' => $post['permalink'], 'image_url' => !empty($post['image_url']) ? $post['image_url'] : ''];
		}, $posts);
	}
	function widget_getPostsToItems($post_params = [], $limit = -1, $offset = -1) {
		return $this->widget_postsToItems($this->post_getPosts($post_params, $limit, $offset));
	}
}
