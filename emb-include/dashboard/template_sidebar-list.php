<?php if(is_string($item) && $item == '-'): ?>
<hr class="my-1">
<?php else: ?>
<li class="nav-item">
	<a class="nav-link <?php echo isset($item['class']) ? $item['class'] : '' ?>" href="<?php echo $item['href'] ?>">
		<i class="fas <?php echo isset($item['fa-class']) ? $item['fa-class'] : 'fa-info-circle' ?> fa-fw"></i> <?php echo $item['text'] ?>
<?php if(!empty($item['badge'])): ?>

		<span class="badge <?php echo !empty($item['badge_class']) ? $item['badge_class'] : 'bg-primary' ?> float-end"><?php echo $item['badge'] ?></span>
<?php endif ?>

	</a>
</li>
<?php endif ?>
