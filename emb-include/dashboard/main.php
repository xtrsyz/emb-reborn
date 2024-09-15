<?php

define('EMB_DASHBOARD_DIR', APP_ROOT_PATH.DIRECTORY_SEPARATOR.Utilities::getRelativePath(realpath(APP_ROOT_PATH), __DIR__));

include "Dashboard.php";
$app->addProperty('dashboard', new Dashboard($app));
$app->dashboard->run();
