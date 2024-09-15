<?php

// $dashboard->invokeRoutes();
$content->page_title = 'Post Manager';
switch($params->method) {
	case 'publish':
		$content->post_card_title = 'Published';
		$posts_params = ['type' => 'post', 'order_by' => "date_published DESC", 'is_published' => true];
		break;
	case 'schedule':
		$content->post_card_title = 'Scheduled';
		$posts_params = ['type' => 'post', 'order_by' => "date_published DESC", 'is_scheduled' => true];
		break;
	case 'draft':
		$content->post_card_title = 'Draft';
		$posts_params = ['type' => 'post', 'order_by' => "post.id DESC", 'is_draft' => true];
		break;
	case 'trash':
		$content->post_card_title = 'Trash';
		$posts_params = ['type' => 'post', 'order_by' => "post.id DESC", 'is_trash' => true];
		break;
	case 'create':
		$content->post_card_title = 'Create New Post';
		break;
	case 'edit':
		$content->post_card_title = 'Edit Post';
		break;
	case 'bulk':
		$content->page_title = 'Spammer With Attitude';
		break;
	default:
		$content->post_card_title = 'All Posts';
		$posts_params = ['type' => 'post', 'order_by' => "date_published DESC", '!is_trash' => true];
}
$content->document_title = "EmbuhBlog: {$content->page_title} - {$content->post_card_title}";
$params->GET->page = $params->GET->page ?: 1;
$limit = $config->dashboard_post_item_per_page;
$offset = ($params->GET->page-1)*$limit;
if(isset($posts_params)) {
	if($params->GET->q)
		$posts_params['keywords'] = $params->GET->q;
	$posts = $app->post_getPosts($posts_params, $limit, $params->GET->page > 1 ? $offset : -1);
	$posts = array_map([$app, 'post_completeData'], $posts);
	foreach($posts as &$post) {
		$post['post_edit_url'] = $dashboard->URI('posts', 'edit', $post['post_id']);
		$post = new Collection($post);
	}
	$content->all_posts = $posts;
	$content->posts_count = $app->post_getPostCount('!is_trash');
	$content->posts_from = $content->posts_count ? $offset+1 : 0;
	$content->posts_to = $offset+count($posts);
	if($params->GET->page > 2)
		$content->prev_page = Utilities::buildURL($app->fullpath, ['q' => $params->GET->q, 'page' => $params->GET->page-1]);
	if($params->GET->page == 2)
		$content->prev_page = Utilities::buildURL($app->fullpath, ['q' => $params->GET->q]);
	if($content->posts_count > $content->posts_to)
		$content->next_page = Utilities::buildURL($app->fullpath, ['q' => $params->GET->q, 'page' => $params->GET->page+1]);
}

// $view = $content->dashboard_view ?: 'posts';
// $dashboard->view($content->dashboard_view ?: '');
