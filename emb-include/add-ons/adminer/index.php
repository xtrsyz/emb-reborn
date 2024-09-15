<?php

define('ADMINER_DIR', EmbuhBlog::helper_getRelativePath(__DIR__));
define('ADMINER_URL', "{$app->basepath}{$config->rule_dashboard}/addons/manage/adminer/sqlite?db=");

// config dashboard sidebar menu
$config->adminer_dashboard_sidebar_menu_show  = !!$config->adminer_dashboard_sidebar_menu_show;
$config->adminer_dashboard_sidebar_menu_title = $config->adminer_dashboard_sidebar_menu_title ?: 'Adminer SQLite';
$config->adminer_dashboard_sidebar_menu_icon  = $config->adminer_dashboard_sidebar_menu_icon  ?: 'fa-database';
