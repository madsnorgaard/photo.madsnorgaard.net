<?php
/*
 * Blog - List
 */
?>

<div class="mauer-blog-feed mauer-blog-feed-list">

	<?php if (is_search()): ?>
		<div class="row">
			<div class="col-sm-8 col-sm-offset-2">
				<h1 class="mauer-posts-search-heading"><?php echo esc_html__('Search results for: ', 'mauer-stills') . '<span class="mauer-search-query-in-results">' .  get_search_query() . '</span>'; ?></h1>
				<?php get_search_form(); ?>
			</div>
		</div>
	<?php endif ?>

	<?php if (is_archive()): ?>
		<div class="row">
			<div class="col-col-xs-12 col-md-10 col-md-offset-1 col-mauer-xl">

				<h1 class="mauer-posts-archive-heading">
					<?php
						if (is_category()) {echo '<span class="mauer-archive-type">' . esc_html_x('Category: ', 'Blog category archive title', 'mauer-stills') . '</span>';}
						if (is_tag()) {echo '<span class="mauer-archive-type">' . esc_html_x('tag: ', 'Blog tag archive title', 'mauer-stills') . '</span>';}
						echo '<span class="mauer-archive-entity">';
						single_cat_title();
						single_month_title();
						echo '</span>';?>
				</h1>

			</div>
		</div>
	<?php endif ?>

	<?php if ( have_posts() ) : ?>

		<div class="mauer-posts-wrapper">
			<?php  while ( have_posts() ) : the_post(); ?>

				<?php
					if (!is_search()) {
						$wrapping_column_class = "col-col-xs-12 col-md-10 col-md-offset-1 col-mauer-xl";
						$textual_column_class = "col-md-6";
					}
					else {
						$wrapping_column_class = "col-sm-8 col-sm-offset-2";
						$textual_column_class = "col-xs-12";
					}
				?>

			<div class="row">
				<div class="<?php echo esc_attr($wrapping_column_class); ?>">


					<div <?php post_class('entry-in-feed'); ?>>
						<?php if (!is_search()): ?><div class="tile-content"><?php endif ?>

							<div class="row">

								<?php if (!is_search()): ?>
									<div class="col-md-6">
									<?php if (has_post_thumbnail()): ?>
										<div class="entry-thumb-link-holder">
											<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="entry-thumb-link">
												<div class="entry-thumb-wrapper">
													<?php the_post_thumbnail('mauer_stills_thumb_3'); ?>
													<div class="entry-thumb-overlay"></div>
												</div>
											</a>
										</div>
									<?php endif ?>
								</div>
								<?php endif ?>


								<div class="<?php echo esc_attr($textual_column_class) ?>">
									<?php if (is_search()): ?>
										<div class="entry-meta">
											<span class="entry-type"><?php echo get_post_type(); ?></span>
										</div>
									<?php endif ?>
									<h1 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
									<?php if (!is_search()): ?>
										<div class="entry-meta">
											<?php if (is_sticky()): ?><span class="entry-sticky"><i class="fa fa-sticky-note-o"></i> <?php esc_html_e('Sticky', 'mauer-stills') ?></span> &mdash; <?php endif ?>
											<span class="entry-date"><a href="<?php the_permalink(); ?>"><?php echo get_the_date(); ?></a></span>
											<span class="entry-cats"><?php echo get_the_category_list(" "); ?></span>
										</div>
									<?php endif ?>
									<div class="entry-excerpt">
										<?php if (is_search() || (function_exists('get_field') && ( get_field('automatic_excerpts', 'option')==1 || get_field('automatic_excerpts', 'option')===NULL )) && !strpos($post->post_content, '<!--more-->')) {the_excerpt();} else {the_content();} ?>
									</div>
								</div>

							</div>

						<?php if (!is_search()): ?></div><?php endif ?>
					</div>

				</div>
			</div>

			<?php endwhile; ?>
		</div>


		<div class="clearfix"></div>

		<?php $prev_link = get_previous_posts_link(); $next_link = get_next_posts_link(); ?>
		<?php if ($prev_link || $next_link): // checking if pagination exists ?>
			<div class="mauer-pagination">

				<?php
					if (!is_search()) {
						$prev_link_text = esc_html__('Newer posts', 'mauer-stills');
						$next_link_text = esc_html__('Older posts', 'mauer-stills');
					} else {
						$prev_link_text = esc_html__('Newer results', 'mauer-stills');
						$next_link_text = esc_html__('Older results', 'mauer-stills');
					}

				?>

				<?php if (($prev_link && !$next_link) || ($next_link && !$prev_link)): ?>
					<div class="row row-wider-cols">
						<div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
							<?php if ($prev_link): ?><div class="nav-previous"><?php previous_posts_link($prev_link_text); ?></div><?php endif ?>
							<?php if ($next_link): ?><div class="nav-next"><?php next_posts_link($next_link_text); ?></div><?php endif ?>
						</div>
					</div>
				<?php endif ?>

				<?php if ($prev_link && $next_link): ?>
					<div class="row row-wider-cols both-navs-present">
						<div class="col-xs-6 col-sm-3 col-sm-offset-3"><div class="nav-previous"><?php previous_posts_link($prev_link_text); ?></div></div>
						<div class="col-xs-6 col-sm-3"><div class="nav-next"><?php next_posts_link($next_link_text); ?></div></div>
					</div>
				<?php endif ?>

				<div class="clearfix"></div>

			</div>
		<?php endif ?>


	<?php else: ?>
		<?php if (is_search()): ?>
			<div class="row">
				<div class="col-sm-8 col-sm-offset-2">
					<p><?php esc_html_e('No results.', 'mauer-stills'); ?></p>
				</div>
			</div>
		<?php else: ?>
			<div class="row">
				<div class="col-xs-12">
					<p class="text-center"><?php esc_html_e('Sorry, no posts matched your criteria', 'mauer-stills'); ?></p>
				</div>
			</div>
		<?php endif; ?>
	<?php endif; ?>



</div>