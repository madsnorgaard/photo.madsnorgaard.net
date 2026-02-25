<?php
/*
 * Projects — 2 Columns
 */
?>



<?php if (have_posts()): ?>


<div class="row">

	<div id="mauer-ajax-buffer"></div>

	<div class="col-xs-12">

		<div class="posts-grid posts-flow-2-cols break-at-sm posts-flow-2-cols-portfolio">
			<div class="posts-grid-content">


				<?php  while ( have_posts() ) : the_post(); ?>

					<?php if (has_post_thumbnail()) {
						$orientation = mauer_stills_get_image_orientation(mauer_stills_get_post_thumbnail_id(get_post_thumbnail_id()));
						$class_to_add = " portfolio-project-tile-with-" . $orientation . "-img";
						if (function_exists('get_field') && get_field('smaller_featured_image') && !isset($_GET["demo_ignore_smaller_thumbs"])) {
							$class_to_add .= " smaller-featured-image";
						}
					} else {
						$class_to_add = " portfolio-project-tile-with-square-img"; // placeholder
					} ?>

					<div <?php post_class('posts-flow-2-cols-item portfolio-project-tile' . $class_to_add); ?>>
						<div class="tile-content">
							<a class="entry-thumb-link" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
								<div class="entry-thumb-wrapper">
									<?php if (has_post_thumbnail()) {
										the_post_thumbnail('mauer_stills_thumb_2');
									} else {
										mauer_stills_the_missing_thumbnail_placeholder();
									} ?>
									<div class="entry-thumb-overlay"></div>
								</div>
								<h3 class="entry-title"><?php the_title() ?></h3>
							</a>
						</div>
					</div>

					<?php $current_post = mauer_stills_real_current_post_number($wp_query); ?>
				<?php endwhile; ?>


			</div>
		</div>

	</div>


	<?php if ($current_post < $wp_query->found_posts): ?>
		<div class="clearfix"></div>
		<div class="col-xs-12 col-lg-4 col-lg-offset-4 mauer-ajax-load-more-col">
			<button data-next-posts-link="<?php echo get_next_posts_page_link(); ?>" id="mauer-ajax-load-more" class="more-grid" data-loading-text="<?php esc_attr_e('Loading', 'mauer-stills'); ?>" data-loaded-text="<?php echo esc_attr_e('Load more', 'mauer-stills'); ?>"><?php esc_html_e('Load more', 'mauer-stills'); ?></button>
		</div>
	<?php endif ?>

</div>


<?php else: ?>


<div class="row">
	<div class="col-xs-12">
		<p class="text-center"><?php esc_html_e('Sorry, nothing to display here.', 'mauer-stills'); ?></p>
	</div>
</div>


<?php endif; ?>