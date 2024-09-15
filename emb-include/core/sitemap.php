<?php

$posts_count = $app->post_getPostsRaw(['fields' => 'COUNT(*) c', 'type' => 'post', 'is_published' => true])[0]['c'];
extract($app->post_getQuery(['type' => 'post', 'is_published' => true]));
// highlight_string($query); exit;
$stmt = $db->run($query, $query_params);

header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8" ?>'."\n";
echo '<?xml-stylesheet type="text/xsl" href="'.$app->baseurl.'/sitemap.xsl"?>'."\n";
?>
<urlset
		xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
		xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
		xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
		xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
							http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
<?php
while($post = $stmt->fetch()):
	$loc = $app->post_createPermalink($post);
	$lastmod = gmdate('c', $post['date_modified'] ?: $post['date_published'] ?: $post['date_created']);
?>
	<url>
		<loc><?php echo $loc ?></loc>
		<lastmod><?php echo $lastmod ?></lastmod>
		<changefreq>daily</changefreq>
		<priority>1.0</priority>
	</url>
<?php endwhile ?>
</urlset>
<?php exit ?>
