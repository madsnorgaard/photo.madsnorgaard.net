<?php
/*
* Single post template
*/
get_header(); ?>

<div class="section-main-content">
	<div class="container-fluid mauer-container-fluid-with-max-width">

	<?php if ( have_posts() ) : ?>

		<?php  while ( have_posts() ) : the_post(); ?>

			<div <?php post_class('entry-full'); ?>>

				<div class="row">
					<div class="col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2">
						<h1 class="entry-title entry-title-followed-by-meta"><?php the_title(); ?></h1>
						<div class="entry-meta">
							<?php if (is_sticky()): ?><span class="entry-sticky"><i class="fa fa-sticky-note-o"></i> <?php esc_html_e('Sticky', 'mauer-stills') ?></span> &mdash; <?php endif ?>
							<span class="entry-date"><?php echo get_the_date(); ?></span>
							<span class="entry-cats"><?php echo get_the_category_list(" "); ?></span>
						</div>
					</div>
				</div>

				<?php if (has_post_thumbnail()) : ?>
					<div class="row">
						<div class="col-xs-12">
							<div class="entry-thumb">
								<div class="entry-thumb-inner">
									<?php the_post_thumbnail('mauer_stills_thumb_6', array('class' => 'mauer-stills-img-adaptable-height-and-width')); ?>
									<?php if (trim(str_replace("#half", "", get_post(get_post_thumbnail_id())->post_excerpt))) : ?>
										<div class="entry-thumb-caption"><?php echo trim(str_replace("#half", "", get_post(get_post_thumbnail_id())->post_excerpt)); ?></div>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>

				<div class="row">
					<div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">

						<div class="add-padding-on-small-resolutions">
							<?php $sharing_of_this_post_disabled = get_post_meta(get_the_ID(), 'sharing_disabled', true); ?>
							<?php if (class_exists('Jetpack') && in_array('sharedaddy', Jetpack::get_active_modules()) && !$sharing_of_this_post_disabled) {$sharedaddy_active = true;} else {$sharedaddy_active = false;} ?>
							<?php if ($sharedaddy_active) {$class_to_add = " special";} else {$class_to_add = "";} ?>
							<div class="entry-content clearfix<?php echo esc_html($class_to_add); ?>">
								<?php the_content(); ?>

								<?php if ($sharedaddy_active && get_the_tags()): ?>
									<div class="entry-content-special-separator"></div>
								<?php endif ?>
							</div>

							<?php if (get_the_tags()): ?>
								<div class="entry-tags">
									<p><?php the_tags('<span class="entry-tags-title">' . esc_html__('Tags', 'mauer-stills') . ':</span> ', '<i>,</i> '); ?></p>
								</div>
							<?php endif ?>

							<?php comments_template(); ?>
						</div>

					</div>
				</div>

			</div><!-- /.entry-full -->




			<!-- related -->
			<?php if (function_exists('get_field')): ?>

				<?php
					if (get_field('show_related_posts')) {
						$related_posts = array();
						if (get_field('related_posts_mode') == 'auto') {$related_posts = mauer_stills_get_related_posts(get_the_ID(), "category", "post_tag", 2);}
						else {foreach (get_field('more_posts_repeater') as $k => $el) {$related_posts[] = $el['post'];}}
					}
				?>

				<?php if (get_field('show_related_posts') && !empty($related_posts)): ?>

					<div class="row">
						<div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">

							<div class="more-posts-wrapper">

								<h3 class="more-posts-heading"><?php if (mauer_stills_get_related_heading(get_the_ID())) {echo mauer_stills_get_related_heading(get_the_ID());} else {esc_html_e( 'Related posts: ', 'mauer-stills' );} ?></h3>

								<div class="row row-narrower-cols">

									<?php foreach ($related_posts as $k => $a_post) : ?>
										<?php if (count($related_posts) == 2): ?><div class="col-xs-12 col-sm-6"><?php endif ?>
										<?php if (count($related_posts) == 1): ?><div class="col-xs-12 col-sm-6 col-sm-offset-3"><?php endif ?>
											<div class="blog-post-in-more">
												<a href="<?php echo get_permalink($a_post); ?>" title="<?php the_title_attribute(array('post'=>$a_post->ID)); ?>">
													<div class="entry-thumb-wrapper blog">
														<?php echo get_the_post_thumbnail($a_post, 'mauer_stills_thumb_5'); ?>
														<div class="entry-thumb-overlay"></div>
													</div>
												</a>
												<?php $class_to_add = ""; if (!has_post_thumbnail($a_post)) {$class_to_add = " no-top-margin";} ?>
												<a href="<?php echo get_permalink($a_post); ?>" title="<?php the_title_attribute(array('post'=>$a_post->ID)); ?>"><h3 class="entry-title <?php echo esc_attr($class_to_add); ?>"><?php echo get_the_title($a_post); ?></h3></a>
												<div class="entry-meta">
													<span class="entry-date"><?php echo get_the_date(); ?></span>
												</div>
											</div>
										<?php if (count($related_posts) == 2): ?></div><?php endif ?>
										<?php if (count($related_posts) == 1): ?></div><?php endif ?>
									<?php endforeach; ?>

								</div>

							</div>

						</div>
					</div>

				<?php endif ?>

			<?php endif ?>
			<!-- related end -->



		<?php endwhile; ?>

	<?php else: ?>
		<div class="row">
			<div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
				<p class="text-center"><?php esc_html_e('Sorry, no posts matched your criteria', 'mauer-stills'); ?></p>
			</div>
		</div>
	<?php endif; ?>

	</div>
</div><!-- /.section-main-content -->

<?php get_footer();?>