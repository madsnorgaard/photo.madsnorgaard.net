<?php
/*
 * Projects Grid — 3 Columns
 */
?>

<?php if (have_posts()): ?>


<div id="mauer-ajax-buffer"></div>

<div class="posts-grid posts-grid-3cols">
	<div class="posts-grid-content">

		<?php  while ( have_posts() ) : the_post(); ?>

		<?php if (has_post_thumbnail()) {
			$orientation = mauer_stills_get_image_orientation(mauer_stills_get_post_thumbnail_id(get_post_thumbnail_id()));
			$class_to_add = " portfolio-project-tile-with-" . $orientation . "-img";

		} else {
			$class_to_add = " portfolio-project-tile-with-square-img"; // placeholder
		} ?>

			<div class="posts-grid-3cols-item">
				<div <?php post_class('portfolio-project-tile' . $class_to_add); ?>>
					<div class="image-imaginary-square-and-title-holder">
						<div class="image-imaginary-square-and-title portfolio-project-in-grid">
							<a class="entry-thumb-link" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
								<div class="entry-thumb-wrapper">
									<?php if (has_post_thumbnail()) {
										the_post_thumbnail('mauer_stills_thumb_4');
									} else {
										mauer_stills_the_missing_thumbnail_placeholder();
									} ?>
									<div class="entry-thumb-overlay"></div>
								</div>
								<h3 class="entry-title"><?php the_title() ?></h3>
							</a>
						</div>
					</div>
				</div>
			</div>

			<?php $current_post = mauer_stills_real_current_post_number($wp_query); ?>
			<?php if ($current_post % 2 == 0): ?><div class="clearfix visible-sm-block visible-xs-block"></div><?php endif ?>
			<?php if ($current_post % 3 == 0): ?><div class="clearfix visible-md-block visible-lg-block"></div><?php endif ?>

		<?php endwhile; ?>

	</div> <!-- /.posts-grid -->
</div>

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

	<?php if (get_query_var('this_is_other_projects_query')) : ?></div> <!-- .mauer-more-projects-wrapper --><?php endif ?>

	<div class="col-xs-12 mauer-ajax-load-more-col">
		<button data-next-posts-link="<?php echo esc_url($next_posts_link_url); ?>" id="mauer-ajax-load-more" class="more-grid<?php echo esc_attr($class_to_add); ?>" data-loading-text="<?php esc_attr_e('Loading', 'mauer-stills'); ?>" data-loaded-text="<?php echo esc_attr_e('Load more', 'mauer-stills'); ?>"><?php esc_html_e('Load more', 'mauer-stills'); ?></button>
	</div>

	<?php if (get_query_var('this_is_other_projects_query')) : ?><div><?php endif ?>

<?php endif ?>



<?php else: ?>
	<div class="row">
		<div class="col-xs-12">
			<p class="text-center" ><?php esc_html_e('Sorry, no posts matched your criteria', 'mauer-stills'); ?></p>
		</div>
	</div>
<?php endif; ?>