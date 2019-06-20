<li>
	<a href=".?action=viewCategory&amp;categoryId=<?=$category['id']?>"><?=$category['name']?></a>
	<?php if(isset($category['childs'])) if($category['childs']): ?>
	<ul>
		<?php echo Category::categories_to_string($category['childs']); ?>
	</ul>
	<?php endif; ?>
</li>