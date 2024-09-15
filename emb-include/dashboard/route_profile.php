<?php

// $content->document_title = 'EmbuhBlog: Profile';
$content->page_title = 'Profile';
$content->body = "<pre>".htmlspecialchars(print_r($user, true))."</pre>";
// $widget->set('dashboard', 'sidebar_profile')->addLink('Change Password', $dashboard->URI('profile/password-change'));
$widget->set('dashboard', 'breadcrumb')->addItem('Profile');
// $dashboard->view();
