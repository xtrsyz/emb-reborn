<?php

// Config for specific domain.
// Value from default-config.php and internal engine class will be overwritten.
// If off/commented = using default-config.php value and default value from internal engine class.

$config->site_name            = 'Embuh Blog';
$config->site_tagline         = 'Welcome to my site';
$config->site_theme           = 'fasthink';
$config->site_https           = true;
$config->site_item_per_page   = 10;

// $config->rule_home_paged      = '/page/%pagenum%/';
// $config->rule_post            = '/%year%/%month%/%title%.html';
// $config->rule_page            = '/p/%title%.html';
// $config->rule_search          = '/search/%title%.html';
// $config->rule_search_paged    = '/search/%pagenum%/%title%.html';
// $config->rule_category        = '/category/%title%';
// $config->rule_category_paged  = '/category/%title%/page/%pagenum%';
// $config->rule_tag             = '/tag/%title%';
// $config->rule_tag_paged       = '/tag/%title%/page/%pagenum%';
// $config->rule_sitemap         = '/hide/your/sitemap.xml';
// $config->rule_sitemap_paged   = '/hide/your/sitemap-%pagenum%.xml';
// $config->rule_dashboard       = '/embuh-dashboard';

// $config->meta_robots_home     = 'index,follow';
// $config->meta_robots_category = 'index,follow';
// $config->meta_robots_tag      = 'index,follow';
// $config->meta_robots_post     = 'index,follow';

// add-ons action/filter is supported
// $app->addAction('hook', function($app) {
// 	// do stuff
// });
// $app->addFilter('hook', function($var) use($app) {
// 	// do stuff for $var, $app
// 	return $var;
// });
