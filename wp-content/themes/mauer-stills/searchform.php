<?php
/*
* Search form template
*/
?>
<form role="search" method="get" class="searchform" action="<?php echo esc_url(home_url('/')); ?>">
	<div>
		<?php if (is_search() || is_404()): ?>
			<?php
				if (is_search()) {$placeholder_phrase = esc_html_x('For a new search type here and hit Enter', 'Search field placeholder', 'mauer-stills');}
				elseif (is_404()) {$placeholder_phrase = esc_html_x('Search: type here and hit Enter', 'Search field placeholder on 404 page', 'mauer-stills');}
			?>
			<p class="search-input-p"><input class="search-input" type="text" value="" name="s" placeholder="<?php echo esc_attr($placeholder_phrase); ?>" /></p>
		<?php else: ?>
			<p class="search-input-p"><input class="search-input" type="text" value="" name="s" placeholder="<?php esc_attr_e('Type and hit Enter', 'mauer-stills'); ?>" /></p>
			<p><input type="submit" class="searchsubmit" value="<?php echo esc_attr_e('Search', 'mauer-stills'); ?>" /></p>
		<?php endif; ?>
	</div>
</form>
