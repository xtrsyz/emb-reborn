<input type='checkbox'/>
<label><i class='fa fa-bars'></i></label>
<ul>
<?php foreach($widget->navbar(['menu' => ['content' => [['href' => $app->homepath, 'text' => 'Home']]]]) as $name => $widget_content): ?>
<?php if(!empty($widget_content['content']) && is_array($widget_content['content'])): ?>
<?php foreach($widget_content['content'] as $i => $item): ?>
	<li>
		<a href="<?php echo $item['href'] ?>" title="<?php echo $item['text'] ?>"><?php echo htmlspecialchars($item['text']) ?></a>
<?php if(isset($item['items']) && is_array($item['items'])): ?>
		<ul>
<?php foreach($item['items'] as $sub): ?>
			<li><a href="<?php echo htmlspecialchars($item['href']) ?>" title="<?php echo htmlspecialchars($item['text']) ?>"><?php echo htmlspecialchars($item['text']) ?></a></li>
<?php endforeach ?>
		</ul>
<?php endif ?>
	</li>
<?php endforeach ?>
<?php endif ?>
<?php endforeach ?>
</ul>
