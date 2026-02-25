<?php
/*
 * Projects Masonry Grid — 4 Columns
 */
?>

<?php if (have_posts()): ?>


<div id="mauer-ajax-buffer"></div>

<div class="masonry-grid masonry-grid-4cols masonry-grid-4cols-portfolio">
	<div class="posts-grid-content">

	<?php  while ( have_posts() ) : the_post(); ?>

		<div class="masonry-grid-item">
			<div class="masonry-grid-item-content">
				<div <?php post_class('portfolio-project-tile'); ?>>
					<a class="entry-thumb-link" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
						<div class="entry-thumb-wrapper">
							<?php if (has_post_thumbnail()) {
								the_post_thumbnail("mauer_stills_thumb_5");
							} else {mauer_stills_the_missing_thumbnail_placeholder();} ?>
							<div class="entry-thumb-overlay"></div>
						</div>
						<h3 class="entry-title"><?php the_title() ?></h3>
					</a>
				</div>
			</div>
		</div>

		<?php $current_post = mauer_stills_real_current_post_number($wp_query); ?>

	<?php endwhile; ?>

	</div>
</div> <!-- /.masonry-grid -->

<?php if ($current_post < $wp_query->found_posts): ?>
	<div class="clearfix"></div>

	<?php
		$next_posts_link_url = get_next_posts_page_link();
		$class_to_add = "";
		if (get_query_var('this_is_other_projects_query')) {
			$next_posts_link_url = get_post_permalink(get_query_var('this_is_other_projects_query_requested_from'));
			$link_paged = get_query_var('this_is_other_projects_query_paged') + 1;
			$next_posts_link_url = add_query_arg('other_projects_paged', $link_paged, $next_posts_link_url);
			$class_to_add = " button-smaller";
		}
	?>

	<?php if (get_query_var('this_is_other_projects_query')) : ?></div><!-- .mauer-more-projects-wrapper --><?php endif ?>

	<div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4 mauer-ajax-load-more-col">
		<button data-next-posts-link="<?php echo esc_url($next_posts_link_url); ?>" id="mauer-ajax-load-more" class="more-masonry<?php echo esc_attr($class_to_add); ?>" data-loading-text="<?php esc_attr_e('Loading', 'mauer-stills'); ?>" data-loaded-text="<?php echo esc_attr_e('Load more', 'mauer-stills'); ?>"><?php esc_html_e('Load more', 'mauer-stills'); ?></button>
	</div>

	<?php if (get_query_var('this_is_other_projects_query')) : ?><div><?php endif ?>

<?php endif ?>



<?php else: ?>
	<div class="row">
		<div class="col-xs-12">
			<p class="text-center"><?php esc_html_e('Sorry, no posts matched your criteria', 'mauer-stills'); ?></p>
		</div>
	</div>
<?php endif; ?>