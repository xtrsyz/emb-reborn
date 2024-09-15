<?php

class Dashboard {
	public $response = [];
	public $user = [];

	// property/method overloading to $this->app
	public function __get($name) {
		if(isset($this->app->prop[$name])) return $this->app->prop[$name];
		if(property_exists($this->app, $name)) return $this->app->{$name};
	}
	public function __call($method, $args) {
		if(method_exists($this->app, $method))
			return call_user_func_array([$this->app, $method], $args);
	}

	function __construct($app) {
		$this->app = $app;
		$this->app->addProperty('auth', new Auth($this->app->db, ['table_name' => 'embuh_user', 'session_key' => 'embuh_blog']));

		// clear all widget data
		$this->app->widget->set([]);
		$this->app->config->append($this->opt_getOptions('dashboard_'));
		$this->app->config->dashboard_post_item_per_page = $this->app->config->dashboard_post_item_per_page ?: 10;
		$this->app->config->dashboard_full_width         = $this->app->config->dashboard_full_width ?: false;

		$this->app->params->route  = $this->app->params->route ?: 'index';
		$this->app->params->routes = $this->app->params->route.'_'.($this->app->params->POST->method ?: $this->app->params->method ?: 'index');

		$this->app->content->dashboard_index_url            = $this->URI();
		$this->app->content->dashboard_login_url            = $this->URI('login');
		$this->app->content->dashboard_logout_url           = $this->URI('logout');
		$this->app->content->dashboard_script_js_url        = $this->URI('script.js');
		$this->app->content->dashboard_script_editor_js_url = $this->URI('script-config-editor.js?v=1.0');
		$this->app->content->dashboard_container_class      = $this->app->config->dashboard_full_width ? 'container-fluid' : 'container';
		$this->app->action('dashboard_construct_ready');
	}
	function run() {
		// authentication
		if($this->app->params->route == 'logout') $this->auth_logout();
		if($this->app->params->route == 'login') $this->auth_login();
		if(!$this->auth_check()) {
			// $this->buildSidebarMenu();
			$this->app->invoke(EMB_DASHBOARD_DIR."/route_login.php");
			exit;
		} else {
			$this->app->addProperty('user', $this->app->user_getUserByUsername($this->app->auth->username));
			$this->app->runtime->is_logged_in = true;
		}

		$this->buildSidebarMenu();
		// pre routing process
		$this->app->action('routing_start');
		// load addons dashboard pre routing files
		foreach($this->app->config->active_addons as $name)
			$this->app->invoke("{$this->app->config->path_addons}/{$name}/dashboard_routing_start.php");
		$this->app->action("route_{$this->app->params->routes}");
		$this->invokeAjaxHandler();
		$this->invokeGenerateSidebarMenu();
		$this->invokePostHandler();
		$this->invokeRouteHandler();
		$this->view();
	}
	protected function invokeGenerateSidebarMenu() {
		$this->app->invoke(__DIR__.'/misc_generate-sidebar-menu.php');
	}
	protected function invokeAjaxHandler() {
		if(!Utilities::isXHRorJSON())
			return;
		$payload = file_get_contents('php://input');
		$data = json_decode($payload, true);
		if($data !== null)
			$this->app->params->POST->set($data);
		switch(true) {
			// case method_exists($this, "AJAX_{$this->app->params->routes}"):
			// 	$this->{"AJAX_{$this->app->params->routes}"}(); exit;
			// case method_exists($this, "AJAX_{$this->app->params->route}"):
			// 	$this->{"AJAX_{$this->app->params->route}"}(); exit;
			case file_exists(EMB_DASHBOARD_DIR."/AJAX_{$this->app->params->routes}.php"):
				$this->app->invoke(EMB_DASHBOARD_DIR."/AJAX_{$this->app->params->routes}.php"); break;
			case file_exists(EMB_DASHBOARD_DIR."/AJAX_{$this->app->params->route}.php"):
				$this->app->invoke(EMB_DASHBOARD_DIR."/AJAX_{$this->app->params->route}.php"); break;
			// case file_exists((string)$this->app->params->{"dashboard_AJAX_{$this->app->params->routes}_path"}):
			// 	$this->app->invoke($this->app->params->{"dashboard_AJAX_{$this->app->params->routes}_path"}); exit;
			// case file_exists((string)$this->app->params->{"dashboard_AJAX_{$this->app->params->route}_path"}):
			// 	$this->app->invoke($this->app->params->{"dashboard_AJAX_{$this->app->params->route}_path"}); exit;
			case $this->app->params->route == 'addons'
				&& in_array($this->app->params->target, $this->app->config->active_addons)
				&& file_exists("{$this->app->config->path_addons}/{$this->app->params->target}/dashboard_AJAX.php"):
				$this->app->invoke("{$this->app->config->path_addons}/{$this->app->params->target}/dashboard_AJAX.php"); break;
			case $this->app->params->route == 'themes'
				&& file_exists("{$this->app->config->path_themes}/{$this->app->config->theme}/options/dashboard_AJAX.php"):
				$this->app->invoke("{$this->app->config->path_themes}/{$this->app->config->theme}/options/dashboard_AJAX.php"); break;
			// case $this->app->runtime->ajax_skip_handler: break;
			default:
				$this->app->JSONResponse([], "Invalid route.", 400); exit;
		}
	}
	protected function invokePostHandler() {
		if($_SERVER['REQUEST_METHOD'] != 'POST')
			return;
		switch(true) {
			// case method_exists($this, "POST_{$this->app->params->routes}"):
			// 	$this->{"POST_{$this->app->params->routes}"}(); break;
			// case method_exists($this, "POST_{$this->app->params->route}"):
			// 	$this->{"POST_{$this->app->params->route}"}(); break;
			case file_exists(EMB_DASHBOARD_DIR."/POST_{$this->app->params->routes}.php"):
				$this->app->invoke(EMB_DASHBOARD_DIR."/POST_{$this->app->params->routes}.php"); break;
			case file_exists(EMB_DASHBOARD_DIR."/POST_{$this->app->params->route}.php"):
				$this->app->invoke(EMB_DASHBOARD_DIR."/POST_{$this->app->params->route}.php"); break;
			// case file_exists((string)$this->app->params->{"dashboard_POST_{$this->app->params->routes}_path"}):
			// 	$this->app->invoke($this->app->params->{"dashboard_POST_{$this->app->params->routes}_path"}); break;
			// case file_exists((string)$this->app->params->{"dashboard_POST_{$this->app->params->route}_path"}):
			// 	$this->app->invoke($this->app->params->{"dashboard_POST_{$this->app->params->route}_path"}); break;
			case $this->app->params->route == 'addons'
				&& in_array($this->app->params->target, $this->app->config->active_addons)
				&& file_exists($this->app->config->path_addons."/{$this->app->params->target}/dashboard_POST.php"):
				$this->app->invoke($this->app->config->path_addons."/{$this->app->params->target}/dashboard_POST.php"); break;
			case $this->app->params->route == 'themes'
				&& file_exists("{$this->app->config->path_themes}/{$this->app->config->theme}/options/dashboard_POST.php"):
				$this->app->invoke("{$this->app->config->path_themes}/{$this->app->config->theme}/options/dashboard_POST.php"); break;
		}
	}
	protected function invokeRouteHandler() {
		switch(true) {
			case $this->app->params->routes == 'addons_manage'
				&& in_array($this->app->params->target, $this->app->config->active_addons)
				&& file_exists("{$this->app->config->path_addons}/{$this->app->params->target}/dashboard_route_{$this->app->params->act}.php"):
				$this->app->invoke("{$this->app->config->path_addons}/{$this->app->params->target}/dashboard_route_{$this->app->params->act}.php"); break;
			case $this->app->params->routes == 'addons_manage'
				&& in_array($this->app->params->target, $this->app->config->active_addons)
				&& file_exists("{$this->app->config->path_addons}/{$this->app->params->target}/dashboard_route.php"):
				$this->app->invoke("{$this->app->config->path_addons}/{$this->app->params->target}/dashboard_route.php"); break;
			case $this->app->params->route == 'themes_manage'
				&& file_exists("{$this->app->config->path_themes}/{$this->app->config->theme}/options/dashboard_route.php"):
				$this->app->invoke("{$this->app->config->path_themes}/{$this->app->config->theme}/options/dashboard_route.php"); break;
			case file_exists(EMB_DASHBOARD_DIR."/route_{$this->app->params->routes}.php"):
				$this->app->invoke(EMB_DASHBOARD_DIR."/route_{$this->app->params->routes}.php"); break;
			case file_exists(EMB_DASHBOARD_DIR."/route_{$this->app->params->route}.php"):
				$this->app->invoke(EMB_DASHBOARD_DIR."/route_{$this->app->params->route}.php"); break;
			// case file_exists((string)$this->app->params->{"dashboard_route_{$this->app->params->routes}_path"}):
			// 	$this->app->invoke($this->app->params->{"dashboard_route_{$this->app->params->routes}_path"}); break;
			// case file_exists((string)$this->app->params->{"dashboard_route_{$this->app->params->route}_path"}):
			// 	$this->app->invoke($this->app->params->{"dashboard_route_{$this->app->params->route}_path"}); break;
			// default:
			// 	$this->app->invoke(EMB_DASHBOARD_DIR."/route_default.php");
		}
	}
	public function view($name = null) {
		$this->app->widget->set('dashboard', 'breadcrumb');
		if(empty($this->app->widget->getContent())) {
			$arr = array_filter([
				$this->app->params->route,
				$this->app->params->method,
				$this->app->params->target,
				$this->app->params->act,
			]);
			$uri_params = [];
			while($value = array_shift($arr)) {
				$uri_params[] = $value;
				// skip addons/theme manage route
				if($uri_params == ['addons', 'manage'] || $uri_params == ['themes', 'manage']) continue;
				$this->app->widget->addLink(ucwords(Utilities::unslug($value)), $this->URI(...$uri_params));
			}
		}
		$this->app->widget->addLink('Dashboard', $this->app->content->dashboard_index_url)->itemToTop();
		$view = '';
		switch(true) {
			case $this->app->params->route == 'addons'
				&& in_array($this->app->params->target, $this->app->config->active_addons)
				&& file_exists($this->app->config->path_addons."/{$this->app->params->target}/dashboard_view_{$this->app->params->act}.php"):
				$view = $this->app->config->path_addons."/{$this->app->params->target}/dashboard_view_{$this->app->params->act}.php";
				$this->app->content->page_title = $this->app->content->page_title ?: "Add-ons: {$this->app->params->target}";
				break;
			case $this->app->params->route == 'addons'
				&& in_array($this->app->params->target, $this->app->config->active_addons)
				&& file_exists($this->app->config->path_addons."/{$this->app->params->target}/dashboard_view.php"):
				$view = $this->app->config->path_addons."/{$this->app->params->target}/dashboard_view.php";
				$this->app->content->page_title = $this->app->content->page_title ?: "Add-ons: {$this->app->params->target}";
				break;
			case $this->app->params->route == 'themes'
				&& file_exists("{$this->app->config->path_themes}/{$this->app->config->theme}/options/dashboard_view.php"):
				$view = "{$this->app->config->path_themes}/{$this->app->config->theme}/options/dashboard_view.php"; break;
			case $this->app->params->dashboard_view:
				$view = $this->app->params->dashboard_view; break;
			case file_exists(EMB_DASHBOARD_DIR."/view_{$name}.php"):
				$view = EMB_DASHBOARD_DIR."/view_{$name}.php"; break;
			case file_exists(EMB_DASHBOARD_DIR."/view_{$this->app->params->routes}.php"):
				$view = EMB_DASHBOARD_DIR."/view_{$this->app->params->routes}.php"; break;
			case file_exists(EMB_DASHBOARD_DIR."/view_{$this->app->params->route}.php"):
				$view = EMB_DASHBOARD_DIR."/view_{$this->app->params->route}.php"; break;
			// default:
			// 	$view = EMB_DASHBOARD_DIR."/view_default.php";
		}
		if(!$view)
			$this->app->content->page_title = $this->app->content->page_title ?: '404 Not Found';
		else
			$this->app->content->page_title = $this->app->content->page_title ?: $this->app->params->act ?: $this->app->params->target ?: $this->app->params->method ?: $this->app->params->route;
		$this->app->content->document_title = $this->app->content->document_title ?: "EmbuhBlog: {$this->app->content->page_title}";

		if(!Utilities::isXHRorJSON()) $this->app->invoke(EMB_DASHBOARD_DIR."/template_header.php");
		$this->app->invoke($view ?: EMB_DASHBOARD_DIR."/view_default.php");
		if(!Utilities::isXHRorJSON()) $this->app->invoke(EMB_DASHBOARD_DIR."/template_footer.php");
	}
	public function getPostsCount() {
		if(!$this->app->runtime->is_logged_in) return [];
		$count['posts_count_all']      = $this->app->post_getPostCount('!is_trash');
		$count['posts_count_publish']  = $this->app->post_getPostCount('is_published');
		$count['posts_count_schedule'] = $this->app->post_getPostCount('is_scheduled');
		$count['posts_count_draft']    = $this->app->post_getPostCount('is_draft');
		$count['posts_count_trash']    = $this->app->post_getPostCount('is_trash');
		return $count;
	}
	public function breadcrumb() {
		return $this->app->widget->set('dashboard', 'breadcrumb');
	}
	public function buildSidebarMenu() {
		if(Utilities::isXHRorJSON())
			return;
		if($this->app->runtime->is_logged_in)
			$this->app->content->append($this->getPostsCount());
		$this->app->widget->set('dashboard', 'sidebar_post')
			->setTitle('Posts')
			->setAttr('link', $this->URI('posts/create'))
			->addItem(['text' => 'All Posts', 'href' => $this->URI('posts'), 'fa-class' => 'fa-th-list', 'badge_class' => 'bg-primary posts_count_all', 'badge' => $this->app->content->posts_count_all])
			->addItem(['text' => 'Published', 'href' => $this->URI('posts/publish'), 'fa-class' => 'fa-paper-plane', 'badge_class' => 'bg-primary posts_count_publish', 'badge' => $this->app->content->posts_count_publish])
			->addItem(['text' => 'Scheduled', 'href' => $this->URI('posts/schedule'), 'fa-class' => 'fa-clock', 'badge_class' => 'bg-primary posts_count_schedule', 'badge' => $this->app->content->posts_count_schedule])
			->addItem(['text' => 'Drafts', 'href' => $this->URI('posts/draft'), 'fa-class' => 'fa-file-alt', 'badge_class' => 'bg-primary posts_count_draft', 'badge' => $this->app->content->posts_count_draft])
			->addItem(['text' => 'Trash', 'href' => $this->URI('posts/trash'), 'fa-class' => 'fa-trash-alt', 'badge_class' => 'bg-primary posts_count_trash', 'badge' => $this->app->content->posts_count_trash])
			->addItem(['text' => 'Bulk Post', 'href' => $this->URI('posts/bulk'), 'fa-class' => 'fa-fast-forward'])
			;
		$this->app->widget->set('dashboard', 'sidebar_page')
			->setTitle('Pages')
			->setAttr('link', $this->URI('pages/create'))
			->addItem(['text' => 'All Pages', 'href' => $this->URI('pages'), 'fa-class' => 'fa-list', 'badge_class' => 'bg-primary pages_count_all', 'badge' => $this->app->content->pages_count_all])
			;
		$this->app->widget->set('dashboard', 'sidebar_common_management')
			->addItem(['text' => 'Categories', 'href' => $this->URI('categories'), 'fa-class' => 'fa-book-open', 'badge_class' => 'bg-primary category_count_all'])
			->addItem(['text' => 'Tags', 'href' => $this->URI('tags'), 'fa-class' => 'fa-tags', 'badge_class' => 'bg-primary tag_count_all'])
			;
		$this->app->widget->set('dashboard', 'sidebar_themes')
			->addItem(['text' => 'Themes', 'href' => $this->URI('themes'), 'fa-class' => 'fa-palette'])
			;
		$this->app->widget->set('dashboard', 'sidebar_addons')
			->addItem(['text' => 'Add-ons', 'href' => $this->URI('addons'), 'fa-class' => 'fa-puzzle-piece'])
			;
		foreach($this->app->config->active_addons ?: [] as $name) {
			if($this->app->config->{"{$name}_dashboard_sidebar_menu_show"})
				$this->app->widget->addItem([
					'text' => $this->app->config->{"{$name}_dashboard_sidebar_menu_title"} ?: ucwords(Utilities::unslug($name)),
					'href' => $this->URI("addons/manage/$name"),
					'fa-class' => $this->app->config->{"{$name}_dashboard_sidebar_menu_icon"} ?: 'fa-puzzle-piece',
				]);
		}
		$this->app->widget->set('dashboard', 'sidebar_users')
			->addItem(['text' => 'Users', 'href' => $this->URI('users'), 'fa-class' => 'fa-users'])
			->addItem(['text' => 'Profile', 'href' => $this->URI('profile'), 'fa-class' => 'fa-user'])
			->addItem(['text' => 'Change Password', 'href' => $this->URI('profile/password-change'), 'fa-class' => 'fa-user-lock'])
			;
		$this->app->widget->set('dashboard', 'sidebar_misc')
			->addItem(['text' => 'Settings', 'href' => $this->URI('settings'), 'fa-class' => 'fa-cog'])
			->addItem(['text' => 'Credits', 'href' => $this->URI('credits'), 'fa-class' => 'fa-copyright'])
			->addItem(['text' => 'Debug', 'href' => $this->URI('debug'), 'fa-class' => 'fa-bug'])
			->addItem(['text' => 'Documentation', 'href' => $this->URI('docs'), 'fa-class' => 'fa-file-alt'])
			->addItem('-')
			->addItem(['text' => 'Logout', 'href' => $this->app->content->dashboard_logout_url, 'fa-class' => 'fa-sign-out-alt'])
			;
	}
	public function addons_activate($name) {
		if(in_array($name, $this->app->config->active_addons ?: []))
			return false;
		$this->app->opt_setOption('active_addons', array_merge($this->app->config->active_addons ?: [], [$name]));
		$this->app->invoke("{$this->app->config->path_addons}/{$name}/on_activate.php");
	}
	public function addons_deactivate($name) {
		if(!in_array($name, $this->app->config->active_addons ?: []))
			return false;
		$this->app->opt_setOption('active_addons', array_diff($this->app->config->active_addons ?: [], [$name]));
		$this->app->invoke("{$this->app->config->path_addons}/{$name}/on_deactivate.php");
	}
	public function __helper_posts_bulk_maintain_item($item) {
		$item = $this->filter('dashboard_post_bulk_data_item', $item);
		if(empty($item)) return [];
		if(!empty($item['article'])) $item['content'] = $item['article'];
		if($this->app->params->POST->draft) $item['published'] = 0;
		if($this->app->params->POST->schedule_from && $this->app->params->POST->schedule_min) {
			$delay = rand($this->app->params->POST->schedule_min*60, ($this->app->params->POST->schedule_max ?: $this->app->params->POST->schedule_min)*60);
			if($this->app->params->POST->schedule_from == 'schedule_from_newest') {
				// get latest post date_published
				$rows = $this->app->post_getPostsRaw(['fields' => 'date_published', 'type' => 'post', 'is_scheduled' => true, 'order_by' => 'date_published DESC'], 1);
				if(!empty($rows[0]['date_published']))
					$item['date_published'] = $rows[0]['date_published'] + $delay;
				else
					$item['date_published'] = time() + $delay;
			}
			elseif($this->app->params->POST->schedule_from == 'schedule_from_now') {
				$item['date_published'] = time() + $delay;
			}
			elseif($this->app->params->POST->schedule_from == 'backdate_from_oldest') {
				// get latest post date_published
				$rows = $this->app->post_getPostsRaw(['fields' => 'date_published', 'type' => 'post', 'is_published' => true, 'order_by' => 'date_published'], 1);
				if(!empty($rows[0]['date_published']))
					$item['date_published'] = $rows[0]['date_published'] - $delay;
				else
					$item['date_published'] = time() - $delay;
			}
			elseif($this->app->params->POST->schedule_from == 'backdate_from_now') {
				$item['date_published'] = time() - $delay;
			}
		}
		return $item;
	}
	const SORT_NONE = 0;
	const SORT_KEY = 1;
	public function __helper_invoke_card_config_editor($includes, $excludes = [], $card_title = null, $read_only = false, $sort = self::SORT_NONE) {
		return call_user_func(static function($app, $includes, $excludes, $card_title, $read_only, $sort) {
			extract($app->prop);
			if(is_string($includes)) {
				$card_title = $card_title ?: "Config [{$includes}*]";
				$prefixes = [$includes];
			} elseif(is_array($includes)) {
				$prefixes = $includes;
				$card_title = $card_title ?: 'Config [Mixed]';
			} else {
				$prefixes = [];
				$card_title = $card_title ?: 'Prefix not set';
			}
			$rows = [];
			foreach($prefixes as $prefix) {
				if(is_array($prefix)) {
					$rows[] = $prefix;
					continue;
				}
				$arr = $config->get($prefix);
				switch($sort) {
					case self::SORT_KEY: ksort($arr); break;
				}
				foreach(array_filter($arr, function($key) use ($prefix, $excludes) {
					return strpos($key, $prefix) === 0 && !self::in_array_prefix($key, $excludes);
				}, ARRAY_FILTER_USE_KEY) as $key => $value)
					$rows[] = [$key, $value];
			}
			include EMB_DASHBOARD_DIR.($read_only ? '/template_card-config-view.php' : '/template_card-config-editor.php');
		}, $this->app, $includes, $excludes, $card_title, $read_only, $sort);
	}
	public function __helper_invoke_card_table($title, $rows = [], $header = [], $footer = []) {
		return $this->__helper_invoke_card([
			'title' => $title,
			'table' => [
				'header' => $header,
				'body' => $rows,
				'footer' => $footer,
			],
		]);
	}
	public function __helper_invoke_card($card) {
		return call_user_func(static function($app, $card) {
			// extract($app->prop);
			extract($card, EXTR_PREFIX_ALL, 'card');
			include EMB_DASHBOARD_DIR.'/template_card.php';
		}, $this->app, $card);
	}
	protected function auth_login() {
		if(Utilities::isXHRorJSON()) {
			if(!$this->app->params->POST->username || !$this->app->params->POST->password)
				return $this->app->JSONResponse([], 'Username or password can not be empty.', 400);
			if($this->app->auth->login($this->app->params->POST->username, $this->app->params->POST->password) === false)
				return $this->app->JSONResponse([], 'Username and password do not match.', 401);
			return $this->app->JSONResponse([], 'You are now logged in.');
		}
		if(!$this->app->params->POST->username || !$this->app->params->POST->password)
			return;
		if($this->app->auth->login($this->app->params->POST->username, $this->app->params->POST->password) === false)
			return $this->response('Invalid username or password.', 'error');
		header('Location: '.$this->app->content->dashboard_index_url);
		exit;
	}
	protected function auth_logout() {
		$this->app->auth->logout();
		header('Location: '.$this->app->content->dashboard_index_url);
		exit;
	}
	protected function auth_check() {
		if(Utilities::isXHRorJSON()) {
			if(!$this->app->auth->check() && !in_array($this->app->params->routes, $this->app->config->dashboard_skip_auth ?: [])) {
				return $this->app->JSONResponse([], 'You must be logged in.', 401);
			}
			return true;
		} else {
			if(!$this->app->auth->check()) {
				$this->app->params->route = 'login';
				unset($this->app->params->method, $this->app->params->target);
				return false;
			}
			return true;
		}
	}

	public function URI($method = null, $act = null, $target = null) {
		$path = implode('/', array_filter([$this->app->config->rule_dashboard, $method, $act, $target]));
		return $this->basepath.$path;
	}
	public function response($message, $type = 'success', $return = null) {
		if(Utilities::isXHRorJSON())
			return $this->app->JSONResponse($this->response, $message, $type == 'success' ? null : 400);
		$this->app->content->dashboard_toastr = "toastr.{$type}(".var_export($message, true).");";
		return $return !== null ? $return : $type == 'success';
	}
	public static function in_array_prefix($needle, $haystack) {
		foreach ($haystack as $value)
			if (strpos($needle, $value) === 0)
				return true;
		return false;
	}
}
